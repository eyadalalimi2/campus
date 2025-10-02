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
        $terms = MedicalTerm::with('year.major')->orderBy('year_id')->orderBy('term_number')->paginate(20);
        return view('admin.medical_terms.index', compact('terms'));
    }

    public function create()
    {
        $years = MedicalYear::with('major')->get()->mapWithKeys(fn($y)=>[$y->id => $y->major->name.' - سنة '.$y->year_number]);
        return view('admin.medical_terms.create', compact('years'));
    }

    public function store(MedicalTermRequest $request)
    {
        MedicalTerm::create($request->validated());
        return redirect()->route('admin.medical_terms.index')->with('success','تم الإنشاء');
    }

    public function edit(MedicalTerm $medical_term)
    {
        $years = MedicalYear::with('major')->get()->mapWithKeys(fn($y)=>[$y->id => $y->major->name.' - سنة '.$y->year_number]);
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