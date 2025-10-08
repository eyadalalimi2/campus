<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AppContentRequest;
use App\Models\AppContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AppContentController extends Controller
{
    public function index()
    {
        $contents = AppContent::query()->ordered()->paginate(20);
        return view('admin.app_contents.index', compact('contents'));
    }

    public function create()
    {
        return view('admin.app_contents.create');
    }

    public function store(AppContentRequest $request)
    {
        $data = $request->validated();

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('app_contents', 'public');
        }

        AppContent::create([
            'title'       => $data['title'],
            'description' => $data['description'] ?? null,
            'image_path'  => $imagePath,
            'link_url'    => $data['link_url'] ?? null,
            'sort_order'  => $data['sort_order'] ?? 0,
            'is_active'   => (bool)$data['is_active'],
        ]);

        return redirect()->route('admin.app_contents.index')->with('success', 'تمت الإضافة بنجاح.');
    }

    public function edit(AppContent $app_content)
    {
        return view('admin.app_contents.edit', ['content' => $app_content]);
    }

    public function update(AppContentRequest $request, AppContent $app_content)
    {
        $data = $request->validated();

        // إزالة الصورة الحالية؟
        if (!empty($data['remove_image']) && $app_content->image_path) {
            Storage::disk('public')->delete($app_content->image_path);
            $app_content->image_path = null;
        }

        // رفع صورة جديدة؟
        if ($request->hasFile('image')) {
            if ($app_content->image_path) {
                Storage::disk('public')->delete($app_content->image_path);
            }
            $app_content->image_path = $request->file('image')->store('app_contents', 'public');
        }

        $app_content->title       = $data['title'];
        $app_content->description = $data['description'] ?? null;
        $app_content->link_url    = $data['link_url'] ?? null;
        $app_content->sort_order  = $data['sort_order'] ?? 0;
        $app_content->is_active   = (bool)$data['is_active'];
        $app_content->save();

        return redirect()->route('admin.app_contents.index')->with('success', 'تم التحديث بنجاح.');
    }

    public function destroy(AppContent $app_content)
    {
        if ($app_content->image_path) {
            Storage::disk('public')->delete($app_content->image_path);
        }
        $app_content->delete();
        return back()->with('success', 'تم الحذف.');
    }
}
