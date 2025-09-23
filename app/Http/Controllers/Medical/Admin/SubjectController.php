<?php
namespace App\Http\Controllers\Medical\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medical\SubjectRequest;
use App\Models\Medical\Subject;

class SubjectController extends Controller {
    public function index(){ $items = Subject::orderBy('name_ar')->paginate(20); return view('medical.admin.subjects.index', compact('items')); }
    public function create(){ return view('medical.admin.subjects.create'); }
    public function store(SubjectRequest $req){ Subject::create($req->validated()); return redirect()->route('medical.subjects.index')->with('ok','تم الإنشاء'); }
    public function edit(Subject $subject){ return view('medical.admin.subjects.edit', compact('subject')); }
    public function update(SubjectRequest $req, Subject $subject){ $subject->update($req->validated()); return redirect()->route('medical.subjects.index')->with('ok','تم التحديث'); }
    public function destroy(Subject $subject){ $subject->delete(); return back()->with('ok','تم الحذف'); }
}
