<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DisciplineRequest;
use App\Models\Discipline;

class DisciplineController extends Controller
{
    public function index()
    {
        $disciplines = Discipline::orderBy('name')->paginate(15);
        return view('admin.disciplines.index', compact('disciplines'));
    }

    public function create()
    {
        return view('admin.disciplines.create');
    }

    public function store(DisciplineRequest $request)
    {
        Discipline::create($request->validated());
        return redirect()->route('admin.disciplines.index')->with('success','تم إضافة المجال بنجاح.');
    }

    public function edit(Discipline $discipline)
    {
        return view('admin.disciplines.edit', compact('discipline'));
    }

    public function update(DisciplineRequest $request, Discipline $discipline)
    {
        $discipline->update($request->validated());
        return redirect()->route('admin.disciplines.index')->with('success','تم تحديث المجال بنجاح.');
    }

    public function destroy(Discipline $discipline)
    {
        $discipline->delete();
        return redirect()->route('admin.disciplines.index')->with('success','تم حذف المجال بنجاح.');
    }
}
