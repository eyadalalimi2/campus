<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MedTopicRequest;
use App\Models\MedTopic;
use App\Models\MedSubject;

class MedTopicController extends Controller
{
    public function index()
    {
        $topics = MedTopic::with('subject')->orderBy('order_index')->paginate(20);
        return view('admin.med_topics.index', compact('topics'));
    }

    public function create()
    {
        $subjects = MedSubject::orderBy('name')->get();
        return view('admin.med_topics.create', compact('subjects'));
    }

    public function store(MedTopicRequest $request)
    {
        MedTopic::create($request->validated());
        return redirect()->route('admin.med_topics.index')->with('success','تم إنشاء الموضوع');
    }

    public function edit(MedTopic $topic)
    {
        $subjects = MedSubject::orderBy('name')->get();
        return view('admin.med_topics.edit', compact('topic','subjects'));
    }

    public function update(MedTopicRequest $request, MedTopic $topic)
    {
        $topic->update($request->validated());
        return redirect()->route('admin.med_topics.index')->with('success','تم التحديث');
    }

    public function destroy(MedTopic $topic)
    {
        $topic->delete();
        return back()->with('success','تم الحذف');
    }
}
