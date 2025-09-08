<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MajorProgram;
use App\Models\Major;
use App\Models\Program;
use Illuminate\Http\Request;

class MajorProgramController extends Controller
{
    public function index(Request $request)
    {
        $items = MajorProgram::with(['major.college', 'program'])
            ->orderByDesc('id')
            ->paginate(20);

        return view('admin.major_program.index', compact('items'));
    }

    public function create()
    {
        return view('admin.major_program.create', [
            'majors'   => Major::orderBy('name')->get(),
            'programs' => Program::orderBy('name')->get(),
            'item'     => new MajorProgram(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'major_id'   => 'required|exists:majors,id',
            'program_id' => 'required|exists:programs,id',
        ]);
        MajorProgram::firstOrCreate($data);
        return redirect()->route('admin.major_program.index')
            ->with('success', 'تم الحفظ.');
    }

    public function edit(MajorProgram $majorProgram)
    {
        return view('admin.major_program.edit', [
            'majors'   => Major::orderBy('name')->get(),
            'programs' => Program::orderBy('name')->get(),
            'item'     => $majorProgram,
        ]);
    }

    public function update(Request $request, MajorProgram $majorProgram)
    {
        $data = $request->validate([
            'major_id'   => 'required|exists:majors,id',
            'program_id' => 'required|exists:programs,id',
        ]);
        $majorProgram->update($data);
        return redirect()->route('admin.major_program.index')->with('success', 'تم التحديث.');
    }

    public function destroy(MajorProgram $majorProgram)
    {
        $majorProgram->delete();
        return back()->with('success', 'تم الحذف.');
    }
}
