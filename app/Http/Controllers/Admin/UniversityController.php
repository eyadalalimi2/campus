<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UniversityRequest;
use App\Models\University;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UniversityController extends Controller
{
    public function index(Request $r)
    {
        $q = University::query()->orderBy('name');

        if ($search = $r->get('q')) {
            $q->where(function ($w) use ($search) {
                $w->where('name', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $universities = $q->paginate(12)->withQueryString();
        return view('admin.universities.index', compact('universities'));
    }

    public function create()
    {
        return view('admin.universities.create');
    }



    public function edit(University $university)
    {
        return view('admin.universities.edit', compact('university'));
    }

    public function store(UniversityRequest $r)
    {
        $data = $r->validated();

        // الحقل الجديد
        $data['is_active'] = $r->has('is_active');

        if ($r->hasFile('logo')) {
            $data['logo_path'] = $r->file('logo')->store('universities', 'public');
        }

        University::create($data);

        return redirect()->route('admin.universities.index')->with('success', 'تم إنشاء الجامعة بنجاح.');
    }

    public function update(UniversityRequest $r, University $university)
    {
        $data = $r->validated();

        // الحقل الجديد
        $data['is_active'] = $r->has('is_active');

        if ($r->hasFile('logo')) {
            if ($university->logo_path && Storage::disk('public')->exists($university->logo_path)) {
                Storage::disk('public')->delete($university->logo_path);
            }
            $data['logo_path'] = $r->file('logo')->store('universities', 'public');
        }

        $university->update($data);

        return redirect()->route('admin.universities.index')->with('success', 'تم تحديث الجامعة بنجاح.');
    }


    public function destroy(University $university)
    {
        if ($university->logo_path) {
            Storage::disk('public')->delete($university->logo_path);
        }
        $university->delete();

        return back()->with('success', 'تم حذف الجامعة.');
    }
}
