<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MedicalTip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MedicalTipController extends Controller
{
    public function index()
    {
        $tips = MedicalTip::orderBy('id', 'desc')->paginate(25);
        return view('admin.medical_tips.index', compact('tips'));
    }

    public function create()
    {
        return view('admin.medical_tips.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string',
            'youtube_url' => 'required|url',
            'cover' => 'nullable|image|max:4096',
            'order' => 'nullable|integer',
        ]);

        if ($request->hasFile('cover')) {
            $data['cover'] = $request->file('cover')->store('medical_tips', 'public');
        }

    $data['order'] = $data['order'] ?? 0;
    MedicalTip::create($data);

        return redirect()->route('admin.medical_tips.index')->with('success', 'تم إضافة نصيحة طبية بنجاح');
    }

    public function edit(MedicalTip $medical_tip)
    {
        return view('admin.medical_tips.edit', ['tip' => $medical_tip]);
    }

    public function update(Request $request, MedicalTip $medical_tip)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string',
            'youtube_url' => 'required|url',
            'cover' => 'nullable|image|max:4096',
            'order' => 'nullable|integer',
        ]);

        if ($request->hasFile('cover')) {
            // delete old
            if ($medical_tip->cover) {
                Storage::disk('public')->delete($medical_tip->cover);
            }
            $data['cover'] = $request->file('cover')->store('medical_tips', 'public');
        }

    $data['order'] = $data['order'] ?? 0;
    $medical_tip->update($data);

        return redirect()->route('admin.medical_tips.index')->with('success', 'تم تحديث النصيحة بنجاح');
    }

    public function destroy(MedicalTip $medical_tip)
    {
        if ($medical_tip->cover) {
            Storage::disk('public')->delete($medical_tip->cover);
        }
        $medical_tip->delete();

        return redirect()->route('admin.medical_tips.index')->with('success', 'تم حذف النصيحة');
    }
}
