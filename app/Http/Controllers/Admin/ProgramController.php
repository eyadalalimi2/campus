<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProgramRequest;
use App\Models\Program;
use App\Models\Discipline;
use Illuminate\Database\QueryException;
use Throwable;

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
        try {
            Program::create($request->validated());
            return redirect()
                ->route('admin.programs.index')
                ->with('success', 'تم إضافة البرنامج بنجاح.');
        } catch (QueryException $e) {
            // 1062 = Duplicate entry (قيد فريد)
            if ((int)($e->errorInfo[1] ?? 0) === 1062) {
                return back()
                    ->withInput()
                    ->with('error', 'لا يمكن إضافة البرنامج: الاسم موجود مسبقًا ضمن نفس المجال.');
            }
            return back()
                ->withInput()
                ->with('error', 'تعذّر حفظ البرنامج بسبب خطأ في قاعدة البيانات.');
        } catch (Throwable $e) {
            return back()
                ->withInput()
                ->with('error', 'حدث خطأ غير متوقع أثناء إضافة البرنامج.');
        }
    }

    public function edit(Program $program)
    {
        $disciplines = Discipline::orderBy('name')->get();
        return view('admin.programs.edit', compact('program', 'disciplines'));
    }

    public function update(ProgramRequest $request, Program $program)
    {
        try {
            $program->update($request->validated());
            return redirect()
                ->route('admin.programs.index')
                ->with('success', 'تم تحديث البرنامج بنجاح.');
        } catch (QueryException $e) {
            if ((int)($e->errorInfo[1] ?? 0) === 1062) {
                return back()
                    ->withInput()
                    ->with('error', 'لا يمكن التحديث: الاسم موجود مسبقًا ضمن نفس المجال.');
            }
            return back()
                ->withInput()
                ->with('error', 'تعذّر تحديث البرنامج بسبب خطأ في قاعدة البيانات.');
        } catch (Throwable $e) {
            return back()
                ->withInput()
                ->with('error', 'حدث خطأ غير متوقع أثناء تحديث البرنامج.');
        }
    }

    public function destroy(Program $program)
    {
        try {
            $program->delete();
            return redirect()
                ->route('admin.programs.index')
                ->with('success', 'تم حذف البرنامج بنجاح.');
        } catch (QueryException $e) {
            // 1451 = Cannot delete or update a parent row: a foreign key constraint fails
            if ((int)($e->errorInfo[1] ?? 0) === 1451) {
                return back()->with('warning', 'لا يمكن حذف البرنامج لوجود بيانات مرتبطة به (مثل عناصر/أصول مرتبطة).');
            }
            return back()->with('error', 'تعذّر حذف البرنامج بسبب خطأ في قاعدة البيانات.');
        } catch (Throwable $e) {
            return back()->with('error', 'حدث خطأ غير متوقع أثناء حذف البرنامج.');
        }
    }
}
