<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UniversityRequest;
use App\Models\University;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UniversityController extends Controller
{
    public function index(Request $r) {
        $q = University::query()->orderBy('name');
        if ($search = $r->get('q')) {
            $q->where(fn($w)=>$w->where('name','like',"%$search%")->orWhere('slug','like',"%$search%")->orWhere('code','like',"%$search%"));
        }
        $universities = $q->paginate(12)->withQueryString();
        return view('admin.universities.index', compact('universities'));
    }

    public function create() { return view('admin.universities.create'); }

    public function store(UniversityRequest $r) {
        $data = $r->validated();
        $data['is_active'] = (bool)$r->boolean('is_active');

        if ($r->hasFile('logo'))    { $data['logo_path'] = $r->file('logo')->store('logos','public'); }
        if ($r->hasFile('favicon')) { $data['favicon_path'] = $r->file('favicon')->store('favicons','public'); }

        University::create($data);
        return redirect()->route('admin.universities.index')->with('success','تم إنشاء الجامعة.');
    }

    public function edit(University $university) {
        return view('admin.universities.edit', compact('university'));
    }

    public function update(UniversityRequest $r, University $university) {
        $data = $r->validated();
        $data['is_active'] = (bool)$r->boolean('is_active');

        if ($r->hasFile('logo')) {
            if ($university->logo_path) { Storage::disk('public')->delete($university->logo_path); }
            $data['logo_path'] = $r->file('logo')->store('logos','public');
        }
        if ($r->hasFile('favicon')) {
            if ($university->favicon_path) { Storage::disk('public')->delete($university->favicon_path); }
            $data['favicon_path'] = $r->file('favicon')->store('favicons','public');
        }

        $university->update($data);
        return redirect()->route('admin.universities.index')->with('success','تم تحديث الجامعة.');
    }

    public function destroy(University $university) {
        if ($university->logo_path)    Storage::disk('public')->delete($university->logo_path);
        if ($university->favicon_path) Storage::disk('public')->delete($university->favicon_path);
        $university->delete();
        return back()->with('success','تم حذف الجامعة.');
    }
}
