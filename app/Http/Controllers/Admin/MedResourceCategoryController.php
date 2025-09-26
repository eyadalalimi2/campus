<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MedResourceCategoryRequest;
use App\Models\MedResourceCategory;

class MedResourceCategoryController extends Controller
{
    public function index()
    {
        $q     = request('q');
        $active = request('active'); // 1 أو 0
        $sort  = request('sort', 'order_index');
        $dir   = request('dir', 'asc');

        $categories = \App\Models\MedResourceCategory::query()
            ->when($q, fn($qr) => $qr->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                    ->orWhere('code', 'like', "%{$q}%");
            }))
            ->when(isset($active) && $active !== '', fn($qr) => $qr->where('active', (int)$active))
            ->orderBy($sort, $dir)
            ->paginate(20)
            ->withQueryString();

        return view('admin.med_resource_categories.index', compact('categories'));
    }


    public function create()
    {
        return view('admin.med_resource_categories.create');
    }

    public function store(MedResourceCategoryRequest $request)
    {
        MedResourceCategory::create($request->validated());
        return redirect()->route('admin.med_resource-categories.index')->with('success', 'تم إنشاء التصنيف');
    }

    public function edit(MedResourceCategory $resource_category)
    {
        return view('admin.med_resource_categories.edit', compact('resource_category'));
    }

    public function update(MedResourceCategoryRequest $request, MedResourceCategory $resource_category)
    {
        $resource_category->update($request->validated());
        return redirect()->route('admin.med_resource-categories.index')->with('success', 'تم التحديث');
    }

    public function destroy(MedResourceCategory $resource_category)
    {
        $resource_category->delete();
        return back()->with('success', 'تم الحذف');
    }
}
