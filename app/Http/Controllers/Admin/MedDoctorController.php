<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MedDoctorRequest;
use App\Models\MedDoctor;
use App\Models\MedSubject;
use Illuminate\Support\Facades\Storage;

class MedDoctorController extends Controller
{
    public function index()
    {
        $q      = request('q');
        $status = request('status');
        $sort   = request('sort', 'order_index');
        $dir    = request('dir', 'asc');

        $doctors = \App\Models\MedDoctor::query()
            ->when(
                $q,
                fn($qr) =>
                $qr->where(function ($w) use ($q) {
                    $w->where('name', 'like', "%{$q}%")
                        ->orWhere('bio', 'like', "%{$q}%")
                        ->orWhere('slug', 'like', "%{$q}%");
                })
            )
            ->when($status, fn($qr) => $qr->where('status', $status))
            ->orderBy($sort, $dir)
            ->paginate(20)
            ->withQueryString();

        return view('admin.med_doctors.index', compact('doctors'));
    }


    public function create()
    {
        $subjects = MedSubject::orderBy('name')->get();
        return view('admin.med_doctors.create', compact('subjects'));
    }

    public function store(MedDoctorRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('avatar')) {
            $data['avatar_path'] = $request->file('avatar')->store('med/images', 'public');
        }
        $doctor = MedDoctor::create($data);
        $doctor->subjects()->sync($request->input('subject_ids', []));

        return redirect()->route('admin.med_doctors.index')->with('success', 'تم إنشاء الدكتور');
    }

    public function edit(MedDoctor $doctor)
    {
        $subjects = MedSubject::orderBy('name')->get();
        $selected = $doctor->subjects()->pluck('id')->toArray();
        return view('admin.med_doctors.edit', compact('doctor', 'subjects', 'selected'));
    }

    public function update(MedDoctorRequest $request, MedDoctor $doctor)
    {
        $data = $request->validated();
        if ($request->hasFile('avatar')) {
            if ($doctor->avatar_path) Storage::disk('public')->delete($doctor->avatar_path);
            $data['avatar_path'] = $request->file('avatar')->store('med/images', 'public');
        }
        $doctor->update($data);
        $doctor->subjects()->sync($request->input('subject_ids', []));

        return redirect()->route('admin.med_doctors.index')->with('success', 'تم التحديث');
    }

    public function destroy(MedDoctor $doctor)
    {
        if ($doctor->avatar_path) Storage::disk('public')->delete($doctor->avatar_path);
        $doctor->subjects()->detach();
        $doctor->delete();
        return back()->with('success', 'تم الحذف');
    }
}
