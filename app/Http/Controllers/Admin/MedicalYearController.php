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
        $years = MedicalYear::with('major')->orderBy('major_id')->orderBy('sort_order')->paginate(20);
        return view('admin.medical_years.index', compact('years'));
    }

    public function create()
    {
        $majors = Major::orderBy('name')->pluck('name','id');
        return view('admin.medical_years.create', compact('majors'));
    }

    public function store(MedicalYearRequest $request)
    {
        MedicalYear::create($request->validated());
        return redirect()->route('admin.medical_years.index')->with('success', 'تم إنشاء السنة بنجاح');
    }

    public function edit(MedicalYear $medical_year)
    {
        $majors = Major::orderBy('name')->pluck('name','id');
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