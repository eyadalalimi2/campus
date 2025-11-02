<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MedicalYearRequest;
use App\Models\MedicalYear;
use App\Models\Major;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        $data = $request->validated();

        // Handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/medical_years');
            // Normalize to storage relative path for Storage::url
            $data['image_path'] = $path;
        }

        MedicalYear::create($data);
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
        $data = $request->validated();

        if ($request->hasFile('image')) {
            // delete old
            if ($medical_year->image_path && Storage::exists($medical_year->image_path)) {
                Storage::delete($medical_year->image_path);
            }
            $path = $request->file('image')->store('public/medical_years');
            $data['image_path'] = $path;
        }

        $medical_year->update($data);
        return redirect()->route('admin.medical_years.index')->with('success', 'تم تحديث السنة');
    }

    public function destroy(MedicalYear $medical_year)
    {
        if ($medical_year->image_path && Storage::exists($medical_year->image_path)) {
            Storage::delete($medical_year->image_path);
        }
        $medical_year->delete();
        return back()->with('success', 'تم الحذف');
    }
}