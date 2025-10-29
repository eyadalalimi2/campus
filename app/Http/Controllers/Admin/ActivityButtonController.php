<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityButton;
use Illuminate\Http\Request;

class ActivityButtonController extends Controller
{
    public function index()
    {
        $buttons = ActivityButton::orderBy('order')->get();
        return view('admin.activity_buttons.index', compact('buttons'));
    }

    /**
     * Show form to create a new activity button
     */
    public function create()
    {
        return view('admin.activity_buttons.create');
    }

    /**
     * Store new activity button
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = str()->slug($data['title']);
        }

        $data['order'] = $data['order'] ?? 0;

        ActivityButton::create($data);

        return redirect()->route('admin.activity_buttons.index')->with('success', 'تمت إضافة الزر بنجاح');
    }

    /**
     * Show edit form for an activity button
     */
    public function edit(ActivityButton $activity_button)
    {
        return view('admin.activity_buttons.edit', compact('activity_button'));
    }

    /**
     * Update an activity button
     */
    public function update(Request $request, ActivityButton $activity_button)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = str()->slug($data['title']);
        }

        $data['order'] = $data['order'] ?? 0;

        $activity_button->update($data);

        return redirect()->route('admin.activity_buttons.index')->with('success', 'تم تحديث الزر');
    }

    /**
     * Delete an activity button
     */
    public function destroy(ActivityButton $activity_button)
    {
        // delete related videos will cascade due to FK onDelete('cascade')
        $activity_button->delete();
        return redirect()->route('admin.activity_buttons.index')->with('success', 'تم حذف الزر');
    }
}
