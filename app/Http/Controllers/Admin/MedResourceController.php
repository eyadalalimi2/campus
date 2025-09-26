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
        $resources = MedResource::with(['category','subject','topic'])
            ->orderBy('order_index')->paginate(20);
        return view('admin.med_resources.index', compact('resources'));
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
            $path = $request->file('file')->store('med/files','public');
            $data['file_url'] = asset('storage/'.$path);
            $data['file_size_bytes'] = $request->file('file')->getSize();
        }

        MedResource::create($data);
        return redirect()->route('admin.med_resources.index')->with('success','تم إنشاء الملف');
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
            $path = $request->file('file')->store('med/files','public');
            $data['file_url'] = asset('storage/'.$path);
            $data['file_size_bytes'] = $request->file('file')->getSize();
        }

        $resource->update($data);
        return redirect()->route('admin.med_resources.index')->with('success','تم التحديث');
    }

    public function destroy(MedResource $resource)
    {
        $resource->delete();
        return back()->with('success','تم الحذف');
    }
}
