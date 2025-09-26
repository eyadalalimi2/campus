<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MedResourceCategoryRequest;
use App\Models\MedResourceCategory;

class MedResourceCategoryController extends Controller
{
    public function index()
    {
        $categories = MedResourceCategory::orderBy('order_index')->paginate(20);
        return view('admin.med_resource_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.med_resource_categories.create');
    }

    public function store(MedResourceCategoryRequest $request)
    {
        MedResourceCategory::create($request->validated());
        return redirect()->route('admin.med_resource-categories.index')->with('success','تم إنشاء التصنيف');
    }

    public function edit(MedResourceCategory $resource_category)
    {
        return view('admin.med_resource_categories.edit', compact('resource_category'));
    }

    public function update(MedResourceCategoryRequest $request, MedResourceCategory $resource_category)
    {
        $resource_category->update($request->validated());
        return redirect()->route('admin.med_resource-categories.index')->with('success','تم التحديث');
    }

    public function destroy(MedResourceCategory $resource_category)
    {
        $resource_category->delete();
        return back()->with('success','تم الحذف');
    }
}
