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
        $q         = request('q');
        $status    = request('status');
        $subjectId = request('subject_id');
        $sort      = request('sort', 'order_index');
        $dir       = request('dir', 'asc');

        $topics = \App\Models\MedTopic::with('subject')
            ->when(
                $q,
                fn($qr) =>
                $qr->where(function ($w) use ($q) {
                    $w->where('title', 'like', "%{$q}%")
                        ->orWhere('slug', 'like', "%{$q}%")
                        ->orWhere('description', 'like', "%{$q}%");
                })
            )
            ->when($status, fn($qr) => $qr->where('status', $status))
            ->when($subjectId, fn($qr) => $qr->where('subject_id', $subjectId))
            ->orderBy($sort, $dir)
            ->paginate(20)
            ->withQueryString();

        $subjects = \App\Models\MedSubject::orderBy('name')->get();

        return view('admin.med_topics.index', compact('topics', 'subjects'));
    }


    public function create()
    {
        $subjects = MedSubject::orderBy('name')->get();
        return view('admin.med_topics.create', compact('subjects'));
    }

    public function store(MedTopicRequest $request)
    {
        MedTopic::create($request->validated());
        return redirect()->route('admin.med_topics.index')->with('success', 'تم إنشاء الموضوع');
    }

    public function edit(MedTopic $topic)
    {
        $subjects = MedSubject::orderBy('name')->get();
        return view('admin.med_topics.edit', compact('topic', 'subjects'));
    }

    public function update(MedTopicRequest $request, MedTopic $topic)
    {
        $topic->update($request->validated());
        return redirect()->route('admin.med_topics.index')->with('success', 'تم التحديث');
    }

    public function destroy(MedTopic $topic)
    {
        $topic->delete();
        return back()->with('success', 'تم الحذف');
    }
}
