<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MaterialRequest;
use App\Models\Material;
use App\Models\University;
use App\Models\College;
use App\Models\Major;
use App\Models\AcademicTerm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaterialController extends Controller
{
    public function index(Request $r)
    {
        $q = Material::query()
            ->with(['university','college','major','terms.calendar'])
            ->orderBy('name');

        if ($r->filled('scope'))         $q->where('scope', $r->scope);
        if ($r->filled('university_id')) $q->where('university_id', $r->university_id);
        if ($r->filled('college_id'))    $q->where('college_id', $r->college_id);
        if ($r->filled('major_id'))      $q->where('major_id', $r->major_id);
        if ($r->filled('level'))         $q->where('level', $r->level);
        // تمت إزالة عمود term من الجدول؛ ندعم فلترة term_id عبر pivot
        if ($r->filled('term_id')) {
            $termId = (int) $r->term_id;
            $q->whereHas('terms', fn($w) => $w->where('academic_terms.id', $termId));
        }
        if ($s = $r->get('q'))           $q->where('name','like',"%$s%");

        $materials = $q->paginate(15)->withQueryString();

        $universities = University::orderBy('name')->get();
        $colleges     = College::orderBy('name')->get();
        // جلب التخصصات المرتبطة بالكلية المختارة فقط
        if ($r->filled('college_id')) {
            $majors = Major::where('college_id', $r->college_id)->orderBy('name')->get();
        } else {
            $majors = Major::orderBy('name')->get();
        }
        $terms        = AcademicTerm::with('calendar')->active()->orderBy('starts_on')->get();

        return view('admin.materials.index', compact('materials','universities','colleges','majors','terms'));
    }

    public function create()
    {
        $universities = University::orderBy('name')->get();
        $colleges     = College::orderBy('name')->get();
        $majors       = Major::orderBy('name')->get();
        $terms        = AcademicTerm::with('calendar')->active()->orderBy('starts_on')->get();

        return view('admin.materials.create', compact('universities','colleges','majors','terms'));
    }

    public function store(MaterialRequest $req)
    {
        $data = $req->validated();

        // منطق النطاق: global → تفريغ المفاتيح؛ university → إلزام university_id (يتحقق في الـ Request)
        if (($data['scope'] ?? 'university') === 'global') {
            $data['university_id'] = null;
            $data['college_id']    = null;
            $data['major_id']      = null;
        }

        $data['is_active'] = (bool) $req->boolean('is_active');

        DB::transaction(function () use ($data, $req) {
            $material = Material::create($data);

            // مزامنة الفصول عبر pivot material_term
            $termIds = array_filter((array) $req->input('term_ids', []));
            $material->terms()->sync($termIds);
        });

        return redirect()->route('admin.materials.index')->with('success','تم إضافة المادة.');
    }

    public function edit(Material $material)
    {
        $material->load(['terms']);

        $universities = University::orderBy('name')->get();
        $colleges     = College::orderBy('name')->get();
        $majors       = Major::orderBy('name')->get();
        $terms        = AcademicTerm::with('calendar')->active()->orderBy('starts_on')->get();

        // لسهولة التحديد في الواجهة
        $selectedTermIds = $material->terms->pluck('id')->toArray();

        return view('admin.materials.edit', compact('material','universities','colleges','majors','terms','selectedTermIds'));
    }

    public function update(MaterialRequest $req, Material $material)
    {
        $data = $req->validated();

        if (($data['scope'] ?? 'university') === 'global') {
            $data['university_id'] = null;
            $data['college_id']    = null;
            $data['major_id']      = null;
        }

        $data['is_active'] = (bool) $req->boolean('is_active');

        DB::transaction(function () use ($data, $req, $material) {
            $material->update($data);

            // مزامنة الفصول
            $termIds = array_filter((array) $req->input('term_ids', []));
            $material->terms()->sync($termIds);
        });

        return redirect()->route('admin.materials.index')->with('success','تم تحديث المادة.');
    }

    public function destroy(Material $material)
    {
        // إزالة روابط pivot قبل الحذف (اختياري؛ ON DELETE CASCADE موجود)
        $material->terms()->detach();
        $material->delete();

        return back()->with('success','تم حذف المادة.');
    }
}
