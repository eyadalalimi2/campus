<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DisciplineRequest;
use App\Models\Discipline;
use Illuminate\Database\QueryException;

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
        try {
            Discipline::create($request->validated());
            return redirect()
                ->route('admin.disciplines.index')
                ->with('success', 'تم إضافة المجال بنجاح.');
        } catch (QueryException $e) {
            // 1062 تكرار قيمة فريدة
            if ($e->getCode() === '23000' && str_contains($e->getMessage(), 'Duplicate entry')) {
                return back()->withInput()->with('warning', 'هذا الاسم موجود مسبقًا.');
            }
            return back()->withInput()->with('error', 'تعذّر الحفظ. يرجى المحاولة لاحقًا.');
        }
    }

    public function edit(Discipline $discipline)
    {
        return view('admin.disciplines.edit', compact('discipline'));
    }

    public function update(DisciplineRequest $request, Discipline $discipline)
    {
        try {
            $discipline->update($request->validated());
            return redirect()
                ->route('admin.disciplines.index')
                ->with('success', 'تم تحديث المجال بنجاح.');
        } catch (QueryException $e) {
            if ($e->getCode() === '23000' && str_contains($e->getMessage(), 'Duplicate entry')) {
                return back()->withInput()->with('warning', 'هذا الاسم موجود مسبقًا.');
            }
            return back()->withInput()->with('error', 'تعذّر التحديث. يرجى المحاولة لاحقًا.');
        }
    }

    public function destroy(Discipline $discipline)
    {
        try {
            $discipline->delete();
            return redirect()
                ->route('admin.disciplines.index')
                ->with('success', 'تم حذف المجال بنجاح.');
        } catch (QueryException $e) {
            // فشل حذف بسبب قيود مراجع أجنبية
            if ($e->getCode() === '23000') {
                return back()->with('warning', 'لا يمكن حذف هذا السجل لوجود بيانات مرتبطة به.');
            }
            return back()->with('error', 'تعذّر الحذف. يرجى المحاولة لاحقًا.');
        }
    }
}
