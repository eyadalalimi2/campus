<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PublicCollege;
use Illuminate\Http\Request;

class PublicCollegeController extends Controller
{
    public function index()
    {
        $items = PublicCollege::query()->orderBy('name')->paginate(20);
        return view('admin.public_colleges.index', compact('items'));
    }

    public function create()
    {
        return view('admin.public_colleges.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:190','unique:public_colleges,name'],
            'slug' => ['nullable','string','max:190','unique:public_colleges,slug'],
            'status' => ['required','in:active,archived'],
        ]);
        PublicCollege::create($data);
        return redirect()->route('admin.public-colleges.index')->with('ok','تم الحفظ.');
    }

    public function edit(PublicCollege $publicCollege)
    {
        return view('admin.public_colleges.edit', ['item'=>$publicCollege]);
    }

    public function update(Request $request, PublicCollege $publicCollege)
    {
        $data = $request->validate([
            'name' => ['required','string','max:190','unique:public_colleges,name,'.$publicCollege->id],
            'slug' => ['nullable','string','max:190','unique:public_colleges,slug,'.$publicCollege->id],
            'status' => ['required','in:active,archived'],
        ]);
        $publicCollege->update($data);
        return back()->with('ok','تم التحديث.');
    }

    public function destroy(PublicCollege $publicCollege)
    {
        // أرشفة بدل الحذف
        $publicCollege->update(['status'=>'archived']);
        return back()->with('ok','تم الأرشفة.');
    }
}
