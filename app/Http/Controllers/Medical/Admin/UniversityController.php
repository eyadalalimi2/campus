<?php
namespace App\Http\Controllers\Medical\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medical\University;
use Illuminate\Http\Request;

class UniversityController extends Controller {
    public function index(){ $items = University::orderBy('name')->paginate(20); return view('medical.admin.universities.index', compact('items')); }
    public function create(){ return view('medical.admin.universities.create'); }
    public function store(Request $r){
        $data = $r->validate([
            'name'=>'required|string|max:191',
            'code'=>'required|string|max:50|unique:med_universities,code',
            'country'=>'required|string|size:2',
            'is_active'=>'nullable|boolean'
        ]);
        $data['is_active'] = (bool)($data['is_active'] ?? true);
        University::create($data);
        return redirect()->route('medical.universities.index')->with('ok','تم الإنشاء');
    }
    public function edit(University $university){ return view('medical.admin.universities.edit', compact('university')); }
    public function update(Request $r, University $university){
        $data = $r->validate([
            'name'=>'required|string|max:191',
            'code'=>'required|string|max:50|unique:med_universities,code,'.$university->id,
            'country'=>'required|string|size:2',
            'is_active'=>'nullable|boolean'
        ]);
        $data['is_active'] = (bool)($data['is_active'] ?? true);
        $university->update($data);
        return redirect()->route('medical.universities.index')->with('ok','تم التحديث');
    }
    public function destroy(University $university){ $university->delete(); return back()->with('ok','تم الحذف'); }
}
