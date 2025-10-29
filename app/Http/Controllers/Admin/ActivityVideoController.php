<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityButton;
use App\Models\ActivityVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ActivityVideoController extends Controller
{
    // index: list videos for a specific button
    public function index(ActivityButton $activity_button)
    {
        $videos = $activity_button->videos()->orderBy('order')->get();
        return view('admin.activity_videos.index', compact('activity_button', 'videos'));
    }

    // show form to create a new video for a button
    public function create(ActivityButton $activity_button)
    {
        return view('admin.activity_videos.create', compact('activity_button'));
    }

    // store new video
    public function store(Request $request, ActivityButton $activity_button)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'youtube_url' => 'required|string|max:1024',
            'short_description' => 'nullable|string',
            'order' => 'nullable|integer',
            'cover_image' => 'nullable|image|max:4096',
        ]);

        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('activity_videos', 'public');
            $data['cover_image'] = $path;
        }

        $data['order'] = $data['order'] ?? 0;
        $data['activity_button_id'] = $activity_button->id;

        ActivityVideo::create($data);

        return redirect()->route('admin.activity_buttons.videos.index', $activity_button->id)
            ->with('success', 'تمت إضافة الفيديو بنجاح');
    }

    // edit form (shallow route: video)
    public function edit(ActivityVideo $video)
    {
        $activity_button = $video->button;
        return view('admin.activity_videos.edit', compact('video', 'activity_button'));
    }

    // update video
    public function update(Request $request, ActivityVideo $video)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'youtube_url' => 'required|string|max:1024',
            'short_description' => 'nullable|string',
            'order' => 'nullable|integer',
            'cover_image' => 'nullable|image|max:4096',
        ]);

        if ($request->hasFile('cover_image')) {
            // delete old if exists
            if ($video->cover_image) {
                Storage::disk('public')->delete($video->cover_image);
            }
            $path = $request->file('cover_image')->store('activity_videos', 'public');
            $data['cover_image'] = $path;
        }

        $data['order'] = $data['order'] ?? $video->order ?? 0;

        $video->update($data);

        return redirect()->route('admin.activity_buttons.videos.index', $video->activity_button_id)
            ->with('success', 'تم تحديث الفيديو');
    }

    // destroy
    public function destroy(ActivityVideo $video)
    {
        if ($video->cover_image) {
            Storage::disk('public')->delete($video->cover_image);
        }
        $buttonId = $video->activity_button_id;
        $video->delete();
        return redirect()->route('admin.activity_buttons.videos.index', $buttonId)
            ->with('success', 'تم حذف الفيديو');
    }
}
