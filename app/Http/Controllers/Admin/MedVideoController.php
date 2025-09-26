<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MedVideoRequest;
use App\Models\MedVideo;
use App\Models\MedDoctor;
use App\Models\MedSubject;
use App\Models\MedTopic;
use Illuminate\Support\Facades\Storage;

class MedVideoController extends Controller
{
    public function index()
    {
        $videos = MedVideo::with(['doctor','subject','topic'])
            ->orderBy('order_index')->paginate(20);
        return view('admin.med_videos.index', compact('videos'));
    }

    public function create()
    {
        return view('admin.med_videos.create', [
            'doctors' => MedDoctor::orderBy('name')->get(),
            'subjects' => MedSubject::orderBy('name')->get(),
            'topics' => MedTopic::orderBy('title')->get(),
        ]);
    }

    public function store(MedVideoRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('med/images','public');
            $data['thumbnail_url'] = asset('storage/'.$path);
        }

        MedVideo::create($data);
        return redirect()->route('admin.med_videos.index')->with('success','تم إنشاء الفيديو');
    }

    public function edit(MedVideo $video)
    {
        return view('admin.med_videos.edit', [
            'video' => $video,
            'doctors' => MedDoctor::orderBy('name')->get(),
            'subjects' => MedSubject::orderBy('name')->get(),
            'topics' => MedTopic::orderBy('title')->get(),
        ]);
    }

    public function update(MedVideoRequest $request, MedVideo $video)
    {
        $data = $request->validated();

        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('med/images','public');
            $data['thumbnail_url'] = asset('storage/'.$path);
        }

        $video->update($data);
        return redirect()->route('admin.med_videos.index')->with('success','تم التحديث');
    }

    public function destroy(MedVideo $video)
    {
        $video->delete();
        return back()->with('success','تم الحذف');
    }
}
