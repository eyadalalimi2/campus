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
        $q         = request('q');
        $status    = request('status');
        $doctorId  = request('doctor_id');
        $subjectId = request('subject_id');
        $topicId   = request('topic_id');
        $dateFrom  = request('from'); // Y-m-d
        $dateTo    = request('to');   // Y-m-d
        $sort      = request('sort', 'order_index');
        $dir       = request('dir', 'asc');

        $videos = \App\Models\MedVideo::with(['doctor', 'subject', 'topic'])
            ->when(
                $q,
                fn($qr) =>
                $qr->where(function ($w) use ($q) {
                    $w->where('title', 'like', "%{$q}%")
                        ->orWhere('youtube_url', 'like', "%{$q}%");
                })
            )
            ->when($status,    fn($qr) => $qr->where('status', $status))
            ->when($doctorId,  fn($qr) => $qr->where('doctor_id', $doctorId))
            ->when($subjectId, fn($qr) => $qr->where('subject_id', $subjectId))
            ->when($topicId,   fn($qr) => $qr->where('topic_id', $topicId))
            ->when($dateFrom,  fn($qr) => $qr->whereDate('published_at', '>=', $dateFrom))
            ->when($dateTo,    fn($qr) => $qr->whereDate('published_at', '<=', $dateTo))
            ->orderBy($sort, $dir)
            ->paginate(20)
            ->withQueryString();

        $doctors  = \App\Models\MedDoctor::orderBy('name')->get();
        $subjects = \App\Models\MedSubject::orderBy('name')->get();
        $topics   = \App\Models\MedTopic::orderBy('title')->get();

        return view('admin.med_videos.index', compact('videos', 'doctors', 'subjects', 'topics'));
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
            $path = $request->file('thumbnail')->store('med/images', 'public');
            $data['thumbnail_url'] = asset('storage/' . $path);
        }

        MedVideo::create($data);
        return redirect()->route('admin.med_videos.index')->with('success', 'تم إنشاء الفيديو');
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
            $path = $request->file('thumbnail')->store('med/images', 'public');
            $data['thumbnail_url'] = asset('storage/' . $path);
        }

        $video->update($data);
        return redirect()->route('admin.med_videos.index')->with('success', 'تم التحديث');
    }

    public function destroy(MedVideo $video)
    {
        $video->delete();
        return back()->with('success', 'تم الحذف');
    }
}
