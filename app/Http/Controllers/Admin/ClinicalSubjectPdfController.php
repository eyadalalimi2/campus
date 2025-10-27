<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ClinicalSubjectPdfRequest;
use App\Models\ClinicalSubject;
use App\Models\ClinicalSubjectPdf;
use Illuminate\Support\Facades\Storage;

class ClinicalSubjectPdfController extends Controller
{
    public function index()
    {
        $pdfs = ClinicalSubjectPdf::with('clinicalSubject')->orderBy('order')->get();
        return view('admin.clinical_subject_pdfs.index', compact('pdfs'));
    }

    public function create()
    {
        $clinicalSubjects = ClinicalSubject::orderBy('order')->get();
        return view('admin.clinical_subject_pdfs.create', compact('clinicalSubjects'));
    }

    public function store(ClinicalSubjectPdfRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('file')) {
            $data['file'] = $request->file('file')->store('clinical_subject_pdfs', 'public');
        }
        ClinicalSubjectPdf::create($data);
        return redirect()->route('admin.clinical_subject_pdfs.index')->with('success', 'تمت إضافة الملف بنجاح');
    }

    public function edit(ClinicalSubjectPdf $clinicalSubjectPdf)
    {
        $clinicalSubjects = ClinicalSubject::orderBy('order')->get();
        return view('admin.clinical_subject_pdfs.edit', compact('clinicalSubjectPdf', 'clinicalSubjects'));
    }

    public function update(ClinicalSubjectPdfRequest $request, ClinicalSubjectPdf $clinicalSubjectPdf)
    {
        $data = $request->validated();
        if ($request->hasFile('file')) {
            if ($clinicalSubjectPdf->file && Storage::disk('public')->exists($clinicalSubjectPdf->file)) {
                Storage::disk('public')->delete($clinicalSubjectPdf->file);
            }
            $data['file'] = $request->file('file')->store('clinical_subject_pdfs', 'public');
        }
        $clinicalSubjectPdf->update($data);
        return redirect()->route('admin.clinical_subject_pdfs.index')->with('success', 'تم تحديث الملف بنجاح');
    }

    public function destroy(ClinicalSubjectPdf $clinicalSubjectPdf)
    {
        if ($clinicalSubjectPdf->file && Storage::disk('public')->exists($clinicalSubjectPdf->file)) {
            Storage::disk('public')->delete($clinicalSubjectPdf->file);
        }
        $clinicalSubjectPdf->delete();
        return redirect()->route('admin.clinical_subject_pdfs.index')->with('success', 'تم حذف الملف بنجاح');
    }
}
