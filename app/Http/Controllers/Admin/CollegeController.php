<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CollegeRequest;
use App\Models\College;
use App\Models\University;
use Illuminate\Http\Request;

class CollegeController extends Controller
{
    public function index(Request $r) {
        $q = College::with('university')->orderBy('name');
        if ($r->filled('university_id')) $q->where('university_id',$r->university_id);
        if ($search = $r->get('q')) $q->where('name','like',"%$search%");
        $colleges = $q->paginate(15)->withQueryString();
        $universities = University::orderBy('name')->get();
        return view('admin.colleges.index', compact('colleges','universities'));
    }

    public function create() {
        $universities = University::orderBy('name')->get();
        return view('admin.colleges.create', compact('universities'));
    }

    public function store(CollegeRequest $r) {
        $data = $r->validated();
        $data['is_active'] = (bool)$r->boolean('is_active');
        College::create($data);
        return redirect()->route('admin.colleges.index')->with('success','تم إنشاء الكلية.');
    }

    public function edit(College $college) {
        $universities = University::orderBy('name')->get();
        return view('admin.colleges.edit', compact('college','universities'));
    }

    public function update(CollegeRequest $r, College $college) {
        $data = $r->validated();
        $data['is_active'] = (bool)$r->boolean('is_active');
        $college->update($data);
        return redirect()->route('admin.colleges.index')->with('success','تم تحديث الكلية.');
    }

    public function destroy(College $college) {
        $college->delete();
        return back()->with('success','تم حذف الكلية.');
    }
}
