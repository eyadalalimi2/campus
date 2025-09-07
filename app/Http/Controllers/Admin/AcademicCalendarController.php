<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AcademicCalendarRequest;
use App\Models\AcademicCalendar;
use App\Models\University;

class AcademicCalendarController extends Controller
{
    public function index()
    {
        $calendars = AcademicCalendar::with('university')->orderBy('year_label','desc')->paginate(15);
        return view('admin.academic_calendars.index', compact('calendars'));
    }

    public function create()
    {
        $universities = University::orderBy('name')->get();
        return view('admin.academic_calendars.create', compact('universities'));
    }

    public function store(AcademicCalendarRequest $request)
    {
        AcademicCalendar::create($request->validated());
        return redirect()->route('admin.academic_calendars.index')->with('success','تم إنشاء التقويم بنجاح.');
    }

    public function edit(AcademicCalendar $academic_calendar)
    {
        $universities = University::orderBy('name')->get();
        return view('admin.academic_calendars.edit', compact('academic_calendar','universities'));
    }

    public function update(AcademicCalendarRequest $request, AcademicCalendar $academic_calendar)
    {
        $academic_calendar->update($request->validated());
        return redirect()->route('admin.academic_calendars.index')->with('success','تم تحديث التقويم بنجاح.');
    }

    public function destroy(AcademicCalendar $academic_calendar)
    {
        $academic_calendar->delete();
        return redirect()->route('admin.academic_calendars.index')->with('success','تم حذف التقويم بنجاح.');
    }
}
