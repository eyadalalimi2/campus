<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MajorRequest;
use App\Models\Major;
use App\Models\University;
use App\Models\College;
use Illuminate\Http\Request;

class MajorController extends Controller
{
    public function index(Request $r)
    {
        $q = Major::with('college.university')->orderBy('name');

        if ($r->filled('college_id')) {
            $q->where('college_id', $r->input('college_id'));
        }

        if ($r->filled('university_id')) {
            $q->whereHas('college', fn($w) => $w->where('university_id', $r->input('university_id')));
        }

        if ($search = $r->get('q')) {
            $q->where('name', 'like', "%{$search}%");
        }

        $majors = $q->paginate(15)->withQueryString();

        $universities = University::orderBy('name')->get();
        $colleges = $r->filled('university_id')
            ? College::where('university_id', $r->input('university_id'))->orderBy('name')->get()
            : College::orderBy('name')->get();

        return view('admin.majors.index', compact('majors', 'universities', 'colleges'));
    }

    public function create()
    {
        $universities = University::orderBy('name')->get();
        $colleges = College::orderBy('name')->get();
        return view('admin.majors.create', compact('universities', 'colleges'));
    }

    public function store(MajorRequest $r)
    {
        $data = $r->validated();
        // عالج الحالة كبوليون بطريقة متوافقة مع كل الإصدارات
        $data['is_active'] = $r->has('is_active') ? 1 : 0;

        Major::create($data);

        return redirect()->route('admin.majors.index')->with('success', 'تم إنشاء التخصص.');
    }

    public function edit(Major $major)
    {
        $universities = University::orderBy('name')->get();
        $colleges = College::where('university_id', $major->college->university_id)->orderBy('name')->get();

        return view('admin.majors.edit', compact('major', 'universities', 'colleges'));
    }

    public function update(MajorRequest $r, Major $major)
    {
        $data = $r->validated();
        $data['is_active'] = $r->has('is_active') ? 1 : 0;

        $major->update($data);

        return redirect()->route('admin.majors.index')->with('success', 'تم تحديث التخصص.');
    }

    public function destroy(Major $major)
    {
        $major->delete();
        return back()->with('success', 'تم حذف التخصص.');
    }
}
