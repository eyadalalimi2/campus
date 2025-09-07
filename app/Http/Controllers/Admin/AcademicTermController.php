<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AcademicTermRequest;
use App\Models\AcademicTerm;
use App\Models\AcademicCalendar;

class AcademicTermController extends Controller
{
    public function index()
    {
        $terms = AcademicTerm::with('calendar.university')->orderBy('starts_on')->paginate(15);
        return view('admin.academic_terms.index', compact('terms'));
    }

    public function create()
    {
        $calendars = AcademicCalendar::with('university')->orderBy('year_label','desc')->get();
        return view('admin.academic_terms.create', compact('calendars'));
    }

    public function store(AcademicTermRequest $request)
    {
        AcademicTerm::create($request->validated());
        return redirect()->route('admin.academic_terms.index')->with('success','تم إضافة الفصل بنجاح.');
    }

    public function edit(AcademicTerm $academic_term)
    {
        $calendars = AcademicCalendar::with('university')->orderBy('year_label','desc')->get();
        return view('admin.academic_terms.edit', compact('academic_term','calendars'));
    }

    public function update(AcademicTermRequest $request, AcademicTerm $academic_term)
    {
        $academic_term->update($request->validated());
        return redirect()->route('admin.academic_terms.index')->with('success','تم تحديث الفصل بنجاح.');
    }

    public function destroy(AcademicTerm $academic_term)
    {
        $academic_term->delete();
        return redirect()->route('admin.academic_terms.index')->with('success','تم حذف الفصل بنجاح.');
    }
}
