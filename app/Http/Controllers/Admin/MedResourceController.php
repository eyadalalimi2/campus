<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MedResourceRequest;
use App\Models\MedResource;
use App\Models\MedResourceCategory;
use App\Models\MedSubject;
use App\Models\MedTopic;
use Illuminate\Support\Facades\Storage;

class MedResourceController extends Controller
{
    public function index()
    {
        $q         = request('q');
        $status    = request('status');
        $subjectId = request('subject_id');
        $topicId   = request('topic_id');
        $categoryId = request('category_id');
        $sort      = request('sort', 'order_index');
        $dir       = request('dir', 'asc');

        $resources = \App\Models\MedResource::with(['category', 'subject', 'topic'])
            ->when(
                $q,
                fn($qr) =>
                $qr->where(function ($w) use ($q) {
                    $w->where('title', 'like', "%{$q}%")
                        ->orWhere('description', 'like', "%{$q}%");
                })
            )
            ->when($status,     fn($qr) => $qr->where('status', $status))
            ->when($subjectId,  fn($qr) => $qr->where('subject_id', $subjectId))
            ->when($topicId,    fn($qr) => $qr->where('topic_id', $topicId))
            ->when($categoryId, fn($qr) => $qr->where('category_id', $categoryId))
            ->orderBy($sort, $dir)
            ->paginate(20)
            ->withQueryString();

        $subjects   = \App\Models\MedSubject::orderBy('name')->get();
        $topics     = \App\Models\MedTopic::orderBy('title')->get();
        $categories = \App\Models\MedResourceCategory::orderBy('order_index')->get();

        return view('admin.med_resources.index', compact('resources', 'subjects', 'topics', 'categories'));
    }


    public function create()
    {
        return view('admin.med_resources.create', [
            'categories' => MedResourceCategory::orderBy('order_index')->get(),
            'subjects' => MedSubject::orderBy('name')->get(),
            'topics' => MedTopic::orderBy('title')->get(),
        ]);
    }

    public function store(MedResourceRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('med/files', 'public');
            $data['file_url'] = asset('storage/' . $path);
            $data['file_size_bytes'] = $request->file('file')->getSize();
        }

        MedResource::create($data);
        return redirect()->route('admin.med_resources.index')->with('success', 'تم إنشاء الملف');
    }

    public function edit(MedResource $resource)
    {
        return view('admin.med_resources.edit', [
            'resource' => $resource,
            'categories' => MedResourceCategory::orderBy('order_index')->get(),
            'subjects' => MedSubject::orderBy('name')->get(),
            'topics' => MedTopic::orderBy('title')->get(),
        ]);
    }

    public function update(MedResourceRequest $request, MedResource $resource)
    {
        $data = $request->validated();

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('med/files', 'public');
            $data['file_url'] = asset('storage/' . $path);
            $data['file_size_bytes'] = $request->file('file')->getSize();
        }

        $resource->update($data);
        return redirect()->route('admin.med_resources.index')->with('success', 'تم التحديث');
    }

    public function destroy(MedResource $resource)
    {
        $resource->delete();
        return back()->with('success', 'تم الحذف');
    }
}
