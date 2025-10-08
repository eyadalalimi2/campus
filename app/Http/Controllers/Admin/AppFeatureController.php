<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppFeature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AppFeatureController extends Controller
{
    public function index()
    {
        $features = AppFeature::orderBy('sort_order')->orderBy('id')->paginate(20);
        return view('admin.app_features.index', compact('features'));
    }

    public function create()
    {
        return view('admin.app_features.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'text'       => ['required', 'string'],
            'sort_order' => ['nullable', 'integer'],
            'is_active'  => ['nullable', 'boolean'],
            'image'      => ['nullable', 'image', 'max:5120'], // 5MB
        ]);

        $payload = [
            'text'       => $data['text'],
            'sort_order' => $data['sort_order'] ?? 0,
            'is_active'  => $data['is_active'] ?? 1,
        ];

        if (!empty($data['image'])) {
            $path = $data['image']->store('app_features', 'public');
            $payload['image_path'] = $path;
        }

        AppFeature::create($payload);

        return redirect()->route('admin.app_features.index')->with('success', 'تمت إضافة الميزة');
    }

    public function edit(AppFeature $app_feature)
    {
        return view('admin.app_features.edit', ['feature' => $app_feature]);
    }

    public function update(Request $request, AppFeature $app_feature)
    {
        $data = $request->validate([
            'text'       => ['required', 'string'],
            'sort_order' => ['nullable', 'integer'],
            'is_active'  => ['nullable', 'boolean'],
            'image'      => ['nullable', 'image', 'max:5120'],
            'remove_image' => ['nullable', 'boolean'],
        ]);

        $payload = [
            'text'       => $data['text'],
            'sort_order' => $data['sort_order'] ?? 0,
            'is_active'  => $data['is_active'] ?? 1,
        ];

        // إزالة الصورة الحالية
        if (!empty($data['remove_image']) && $app_feature->image_path) {
            Storage::disk('public')->delete($app_feature->image_path);
            $payload['image_path'] = null;
        }

        // رفع صورة جديدة
        if (!empty($data['image'])) {
            if ($app_feature->image_path) {
                Storage::disk('public')->delete($app_feature->image_path);
            }
            $payload['image_path'] = $data['image']->store('app_features', 'public');
        }

        $app_feature->update($payload);

        return redirect()->route('admin.app_features.index')->with('success', 'تم تحديث الميزة');
    }

    public function destroy(AppFeature $app_feature)
    {
        if ($app_feature->image_path) {
            Storage::disk('public')->delete($app_feature->image_path);
        }
        $app_feature->delete();

        return redirect()->route('admin.app_features.index')->with('success', 'تم حذف الميزة');
    }
}
