<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClinicalSubject;
use App\Http\Requests\Admin\ClinicalSubjectRequest;
use Illuminate\Http\Request;

class ClinicalSubjectController extends Controller
{
    public function index()
    {
        $subjects = ClinicalSubject::orderBy('order')->get();
        return view('admin.clinical_subjects.index', compact('subjects'));
    }

    public function create()
    {
        return view('admin.clinical_subjects.create');
    }

    public function store(ClinicalSubjectRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('clinical_subjects', 'public');
        }
        ClinicalSubject::create($data);
        return redirect()->route('admin.clinical_subjects.index')->with('success', 'تمت إضافة المادة بنجاح');
    }

    public function edit(ClinicalSubject $clinicalSubject)
    {
        return view('admin.clinical_subjects.edit', compact('clinicalSubject'));
    }

    public function update(ClinicalSubjectRequest $request, ClinicalSubject $clinicalSubject)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('clinical_subjects', 'public');
        }
        $clinicalSubject->update($data);
        return redirect()->route('admin.clinical_subjects.index')->with('success', 'تم تعديل المادة بنجاح');
    }

    public function destroy(ClinicalSubject $clinicalSubject)
    {
        $clinicalSubject->delete();
        return redirect()->route('admin.clinical_subjects.index')->with('success', 'تم حذف المادة بنجاح');
    }
}
