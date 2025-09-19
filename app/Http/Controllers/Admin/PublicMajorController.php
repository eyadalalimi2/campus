<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PublicCollege;
use App\Models\PublicMajor;
use Illuminate\Http\Request;

class PublicMajorController extends Controller
{
    public function index(Request $request)
    {
        $q = PublicMajor::query()->with('publicCollege');
        if ($request->filled('public_college_id')) {
            $q->where('public_college_id', $request->integer('public_college_id'));
        }
        $items = $q->orderBy('name')->paginate(20);
        $colleges = PublicCollege::active()->orderBy('name')->get();
        return view('admin.public_majors.index', compact('items','colleges'));
    }

    public function create()
    {
        $colleges = PublicCollege::active()->orderBy('name')->get();
        return view('admin.public_majors.create', compact('colleges'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'public_college_id' => ['required','exists:public_colleges,id'],
            'name' => ['required','string','max:190'],
            'slug' => ['nullable','string','max:190','unique:public_majors,slug'],
            'status' => ['required','in:active,archived'],
        ]);
        // ضمان فريد مركب (public_college_id, name)
        if (PublicMajor::where('public_college_id',$data['public_college_id'])->where('name',$data['name'])->exists()) {
            return back()->withErrors(['name'=>'موجود مسبقًا لهذه الكلية.'])->withInput();
        }
        PublicMajor::create($data);
        return redirect()->route('admin.public-majors.index')->with('ok','تم الحفظ.');
    }

    public function edit(PublicMajor $publicMajor)
    {
        $colleges = PublicCollege::active()->orderBy('name')->get();
        return view('admin.public_majors.edit', ['item'=>$publicMajor,'colleges'=>$colleges]);
    }

    public function update(Request $request, PublicMajor $publicMajor)
    {
        $data = $request->validate([
            'public_college_id' => ['required','exists:public_colleges,id'],
            'name' => ['required','string','max:190'],
            'slug' => ['nullable','string','max:190','unique:public_majors,slug,'.$publicMajor->id],
            'status' => ['required','in:active,archived'],
        ]);
        if (PublicMajor::where('public_college_id',$data['public_college_id'])
            ->where('name',$data['name'])
            ->where('id','<>',$publicMajor->id)->exists()) {
            return back()->withErrors(['name'=>'موجود مسبقًا لهذه الكلية.'])->withInput();
        }
        $publicMajor->update($data);
        return back()->with('ok','تم التحديث.');
    }

    public function destroy(PublicMajor $publicMajor)
    {
        $publicMajor->update(['status'=>'archived']);
        return back()->with('ok','تم الأرشفة.');
    }
}
