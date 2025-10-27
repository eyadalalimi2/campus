<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreStudyGuideRequest;
use App\Http\Requests\Admin\UpdateStudyGuideRequest;
use App\Models\StudyGuide;

class StudyGuideController extends Controller
{
    public function index()
    {
        $items = StudyGuide::query()->latest()->paginate(20);
        return view('admin.study_guides.index', compact('items'));
    }

    public function create()
    {
        return view('admin.study_guides.create');
    }

    public function store(StoreStudyGuideRequest $request)
    {
        StudyGuide::create($request->validated());
        return redirect()->route('admin.study_guides.index')->with('success', 'تمت الإضافة بنجاح.');
    }

    public function edit(StudyGuide $study_guide)
    {
        return view('admin.study_guides.edit', ['item' => $study_guide]);
    }

    public function update(UpdateStudyGuideRequest $request, StudyGuide $study_guide)
    {
        $study_guide->update($request->validated());
        return redirect()->route('admin.study_guides.index')->with('success', 'تم التحديث بنجاح.');
    }

    public function destroy(StudyGuide $study_guide)
    {
        $study_guide->delete();
        return back()->with('success', 'تم الحذف.');
    }
}
