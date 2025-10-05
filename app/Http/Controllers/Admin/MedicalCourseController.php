<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class MedicalCourseController extends Controller
{
    public function index()
    {
        $courses = Course::orderBy('sort_order')->orderByDesc('id')->paginate(20);
        return view('admin.courses.index', compact('courses'));
    }

    public function create()
    {
        return view('admin.courses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'      => 'required|string|max:255',
            'sort_order' => 'nullable|integer',
        ]);

        Course::create($request->only('title','sort_order') + ['is_active'=>true]);

        return redirect()->route('admin.courses.index')->with('success','تم إنشاء الكورس');
    }

    public function edit(Course $course)
    {
        return view('admin.courses.edit', compact('course'));
    }

    public function update(Request $request, Course $course)
    {
        $request->validate([
            'title'      => 'required|string|max:255',
            'sort_order' => 'nullable|integer',
        ]);

        $course->update($request->only('title','sort_order','is_active'));

        return redirect()->route('admin.courses.index')->with('success','تم تحديث الكورس');
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return redirect()->route('admin.courses.index')->with('success','تم حذف الكورس');
    }
}
