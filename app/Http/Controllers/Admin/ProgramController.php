<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProgramRequest;
use App\Models\Program;
use App\Models\Discipline;

class ProgramController extends Controller
{
    public function index()
    {
        $programs = Program::with('discipline')->orderBy('name')->paginate(15);
        return view('admin.programs.index', compact('programs'));
    }

    public function create()
    {
        $disciplines = Discipline::orderBy('name')->get();
        return view('admin.programs.create', compact('disciplines'));
    }

    public function store(ProgramRequest $request)
    {
        Program::create($request->validated());
        return redirect()->route('admin.programs.index')->with('success','تم إضافة البرنامج بنجاح.');
    }

    public function edit(Program $program)
    {
        $disciplines = Discipline::orderBy('name')->get();
        return view('admin.programs.edit', compact('program','disciplines'));
    }

    public function update(ProgramRequest $request, Program $program)
    {
        $program->update($request->validated());
        return redirect()->route('admin.programs.index')->with('success','تم تحديث البرنامج بنجاح.');
    }

    public function destroy(Program $program)
    {
        $program->delete();
        return redirect()->route('admin.programs.index')->with('success','تم حذف البرنامج بنجاح.');
    }
}
