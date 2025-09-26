<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MedSubjectRequest;
use App\Models\MedSubject;
use App\Models\MedDevice;
use Illuminate\Support\Facades\Storage;

class MedSubjectController extends Controller
{
    public function index()
    {
        $subjects = MedSubject::orderBy('order_index')->paginate(20);
        return view('admin.med_subjects.index', compact('subjects'));
    }

    public function create()
    {
        $devices = MedDevice::orderBy('name')->get();
        return view('admin.med_subjects.create', compact('devices'));
    }

    public function store(MedSubjectRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('med/images','public');
        }
        $subject = MedSubject::create($data);
        $subject->devices()->sync($request->input('device_ids',[]));

        return redirect()->route('admin.med_subjects.index')->with('success','تم إنشاء المادة');
    }

    public function edit(MedSubject $subject)
    {
        $devices = MedDevice::orderBy('name')->get();
        $selected = $subject->devices()->pluck('id')->toArray();
        return view('admin.med_subjects.edit', compact('subject','devices','selected'));
    }

    public function update(MedSubjectRequest $request, MedSubject $subject)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            if ($subject->image_path) Storage::disk('public')->delete($subject->image_path);
            $data['image_path'] = $request->file('image')->store('med/images','public');
        }
        $subject->update($data);
        $subject->devices()->sync($request->input('device_ids',[]));

        return redirect()->route('admin.med_subjects.index')->with('success','تم التحديث');
    }

    public function destroy(MedSubject $subject)
    {
        if ($subject->image_path) Storage::disk('public')->delete($subject->image_path);
        $subject->devices()->detach();
        $subject->delete();
        return back()->with('success','تم الحذف');
    }
}
