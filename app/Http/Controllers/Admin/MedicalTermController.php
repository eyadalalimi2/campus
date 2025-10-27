<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MedicalTermRequest;
use App\Models\MedicalTerm;
use App\Models\MedicalYear;

class MedicalTermController extends Controller
{
    public function index()
    {
        // Eager load الجامعة والفرع عبر التخصص المرتبط بالسنة لعرضها بكفاءة
        $terms = MedicalTerm::with('year.major.college.branch.university')
            ->orderBy('year_id')
            ->orderBy('term_number')
            ->paginate(20);
        return view('admin.medical_terms.index', compact('terms'));
    }

    public function create()
    {
        // بناء تسميات السنوات بالشكل: جامعة - فرع - تخصص - سنة X
        $years = MedicalYear::with('major.college.branch.university')
            ->orderBy('major_id')
            ->orderBy('year_number')
            ->get()
            ->mapWithKeys(function ($y) {
                $major = $y->major;
                $college = optional($major)->college;
                $branch = optional($college)->branch;
                $universityName = optional(optional($branch)->university)->name;
                $branchName = optional($branch)->name;
                $majorName = optional($major)->name;
                $parts = array_filter([$universityName, $branchName, $majorName, 'سنة '.$y->year_number], fn($v)=>filled($v));
                return [$y->id => implode(' - ', $parts)];
            });
        return view('admin.medical_terms.create', compact('years'));
    }

    public function store(MedicalTermRequest $request)
    {
        MedicalTerm::create($request->validated());
        return redirect()->route('admin.medical_terms.index')->with('success','تم الإنشاء');
    }

    public function edit(MedicalTerm $medical_term)
    {
        // بناء تسميات السنوات بالشكل: جامعة - فرع - تخصص - سنة X
        $years = MedicalYear::with('major.college.branch.university')
            ->orderBy('major_id')
            ->orderBy('year_number')
            ->get()
            ->mapWithKeys(function ($y) {
                $major = $y->major;
                $college = optional($major)->college;
                $branch = optional($college)->branch;
                $universityName = optional(optional($branch)->university)->name;
                $branchName = optional($branch)->name;
                $majorName = optional($major)->name;
                $parts = array_filter([$universityName, $branchName, $majorName, 'سنة '.$y->year_number], fn($v)=>filled($v));
                return [$y->id => implode(' - ', $parts)];
            });
        return view('admin.medical_terms.edit', ['term' => $medical_term, 'years' => $years]);
    }

    public function update(MedicalTermRequest $request, MedicalTerm $medical_term)
    {
        $medical_term->update($request->validated());
        return redirect()->route('admin.medical_terms.index')->with('success','تم التحديث');
    }

    public function destroy(MedicalTerm $medical_term)
    {
        $medical_term->delete();
        return back()->with('success','تم الحذف');
    }
}