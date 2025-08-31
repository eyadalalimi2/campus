<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MaterialRequest;
use App\Models\Material;
use App\Models\University;
use App\Models\College;
use App\Models\Major;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function index(Request $r)
    {
        $q = Material::with(['university','college','major'])->orderBy('name');

        if ($r->filled('scope')) $q->where('scope',$r->scope);
        if ($r->filled('university_id')) $q->where('university_id',$r->university_id);
        if ($r->filled('college_id')) $q->where('college_id',$r->college_id);
        if ($r->filled('major_id')) $q->where('major_id',$r->major_id);
        if ($r->filled('level')) $q->where('level',$r->level);
        if ($r->filled('term'))  $q->where('term',$r->term);
        if ($s = $r->get('q')) $q->where('name','like',"%$s%");

        $materials = $q->paginate(15)->withQueryString();

        $universities = University::orderBy('name')->get();
        $colleges     = College::orderBy('name')->get();
        $majors       = Major::orderBy('name')->get();

        return view('admin.materials.index', compact('materials','universities','colleges','majors'));
    }

    public function create()
    {
        $universities = University::orderBy('name')->get();
        $colleges     = College::orderBy('name')->get();
        $majors       = Major::orderBy('name')->get();
        return view('admin.materials.create', compact('universities','colleges','majors'));
    }

    public function store(MaterialRequest $req)
    {
        $data = $req->validated();
        $data['is_active'] = (bool)$req->boolean('is_active');
        Material::create($data);
        return redirect()->route('admin.materials.index')->with('success','تم إضافة المادة.');
    }

    public function edit(Material $material)
    {
        $universities = University::orderBy('name')->get();
        $colleges     = College::orderBy('name')->get();
        $majors       = Major::orderBy('name')->get();
        return view('admin.materials.edit', compact('material','universities','colleges','majors'));
    }

    public function update(MaterialRequest $req, Material $material)
    {
        $data = $req->validated();
        $data['is_active'] = (bool)$req->boolean('is_active');
        $material->update($data);
        return redirect()->route('admin.materials.index')->with('success','تم تحديث المادة.');
    }

    public function destroy(Material $material)
    {
        $material->delete();
        return back()->with('success','تم حذف المادة.');
    }
}
