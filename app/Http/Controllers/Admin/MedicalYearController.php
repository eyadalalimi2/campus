<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MedicalYearRequest;
use App\Models\MedicalYear;
use App\Models\Major;
use Illuminate\Http\Request;

class MedicalYearController extends Controller
{
    public function index()
    {
        // Eager load الجامعة والفرع عبر علاقة التخصص ← الكلية ← الفرع ← الجامعة لعرضها في الفهرس بكفاءة
        $years = MedicalYear::with('major.college.branch.university')
            ->orderBy('major_id')
            ->orderBy('sort_order')
            ->paginate(20);
        return view('admin.medical_years.index', compact('years'));
    }

    public function create()
    {
        // إظهار اسم الجامعة والفرع مع كل تخصص داخل قائمة الاختيار
        $majors = Major::with('college.branch.university')
            ->orderBy('name')
            ->get()
            ->mapWithKeys(function ($m) {
                $university = optional(optional(optional($m->college)->branch)->university)->name;
                $branch     = optional(optional($m->college)->branch)->name;
                // تكوين التسمية: جامعة - فرع - تخصص (مع تجاهل القيم الفارغة)
                $labelParts = array_filter([$university, $branch, $m->name], fn ($v) => filled($v));
                $label      = implode(' - ', $labelParts);
                return [$m->id => $label];
            });
        return view('admin.medical_years.create', compact('majors'));
    }

    public function store(MedicalYearRequest $request)
    {
        MedicalYear::create($request->validated());
        return redirect()->route('admin.medical_years.index')->with('success', 'تم إنشاء السنة بنجاح');
    }

    public function edit(MedicalYear $medical_year)
    {
        // إظهار اسم الجامعة والفرع مع كل تخصص داخل قائمة الاختيار
        $majors = Major::with('college.branch.university')
            ->orderBy('name')
            ->get()
            ->mapWithKeys(function ($m) {
                $university = optional(optional(optional($m->college)->branch)->university)->name;
                $branch     = optional(optional($m->college)->branch)->name;
                $labelParts = array_filter([$university, $branch, $m->name], fn ($v) => filled($v));
                $label      = implode(' - ', $labelParts);
                return [$m->id => $label];
            });
        return view('admin.medical_years.edit', ['year' => $medical_year, 'majors' => $majors]);
    }

    public function update(MedicalYearRequest $request, MedicalYear $medical_year)
    {
        $medical_year->update($request->validated());
        return redirect()->route('admin.medical_years.index')->with('success', 'تم تحديث السنة');
    }

    public function destroy(MedicalYear $medical_year)
    {
        $medical_year->delete();
        return back()->with('success', 'تم الحذف');
    }
}