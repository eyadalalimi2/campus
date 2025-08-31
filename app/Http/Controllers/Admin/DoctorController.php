<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\University;
use App\Models\College;
use App\Models\Major;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class DoctorController extends Controller
{
    public function index(Request $r)
    {
        $q = Doctor::query()->with(['university','college','major','majors'])->orderBy('name');

        if ($r->filled('type'))        $q->where('type', $r->type);
        if ($r->filled('university_id')) $q->where('university_id', $r->university_id);
        if ($r->filled('college_id'))  $q->where('college_id', $r->college_id);
        if ($r->filled('major_id')) {
            // فلترة إما على major_id (للجامعي) أو على pivot (للمستقل)
            $q->where(function($w) use ($r){
                $w->where('major_id', $r->major_id)
                  ->orWhereHas('majors', fn($m)=>$m->where('majors.id',$r->major_id));
            });
        }
        if ($search = $r->get('q'))    $q->where('name','like',"%$search%");

        $doctors = $q->paginate(15)->withQueryString();
        $universities = University::orderBy('name')->get();
        $colleges     = $r->filled('university_id') ? College::where('university_id',$r->university_id)->orderBy('name')->get() : College::orderBy('name')->get();
        $majors       = Major::orderBy('name')->get();

        return view('admin.doctors.index', compact('doctors','universities','colleges','majors'));
    }

    public function create()
    {
        $universities = University::orderBy('name')->get();
        $colleges = College::orderBy('name')->get();
        $majors = Major::with('college.university')->orderBy('name')->get();
        return view('admin.doctors.create', compact('universities','colleges','majors'));
    }

    public function store(Request $r)
    {
        $r->validate([
            'name'  => 'required|string|max:200',
            'type'  => ['required', Rule::in(['university','independent'])],
            // جامعي
            'university_id' => 'nullable|required_if:type,university|exists:universities,id',
            'college_id'    => 'nullable|required_if:type,university|exists:colleges,id',
            'major_id'      => 'nullable|required_if:type,university|exists:majors,id',
            // مستقل
            'major_ids'     => 'nullable|array',
            'major_ids.*'   => 'exists:majors,id',
            // عامة
            'degree'        => 'nullable|string|max:150',
            'degree_year'   => 'nullable|digits:4|integer|min:1900|max:'.date('Y'),
            'phone'         => 'nullable|string|max:30',
            'photo'         => 'nullable|image|max:2048',
            'is_active'     => 'nullable|boolean',
        ]);

        $data = $r->only([
            'name','type','university_id','college_id','major_id','degree','degree_year','phone'
        ]);
        $data['is_active'] = (bool)$r->boolean('is_active');

        if ($r->hasFile('photo')) {
            $data['photo_path'] = $r->file('photo')->store('doctors','public');
        }

        $doctor = Doctor::create($data);

        if ($doctor->type === 'independent' && $r->filled('major_ids')) {
            $doctor->majors()->sync($r->major_ids);
        }

        return redirect()->route('admin.doctors.index')->with('success','تم إضافة الدكتور.');
    }

    public function edit(Doctor $doctor)
    {
        $universities = University::orderBy('name')->get();
        $colleges = College::orderBy('name')->get();
        $majors = Major::with('college.university')->orderBy('name')->get();
        $selectedMajors = $doctor->majors()->pluck('majors.id')->toArray();
        return view('admin.doctors.edit', compact('doctor','universities','colleges','majors','selectedMajors'));
    }

    public function update(Request $r, Doctor $doctor)
    {
        $r->validate([
            'name'  => 'required|string|max:200',
            'type'  => ['required', Rule::in(['university','independent'])],
            'university_id' => 'nullable|required_if:type,university|exists:universities,id',
            'college_id'    => 'nullable|required_if:type,university|exists:colleges,id',
            'major_id'      => 'nullable|required_if:type,university|exists:majors,id',
            'major_ids'     => 'nullable|array',
            'major_ids.*'   => 'exists:majors,id',
            'degree'        => 'nullable|string|max:150',
            'degree_year'   => 'nullable|digits:4|integer|min:1900|max:'.date('Y'),
            'phone'         => 'nullable|string|max:30',
            'photo'         => 'nullable|image|max:2048',
            'is_active'     => 'nullable|boolean',
        ]);

        $data = $r->only(['name','type','university_id','college_id','major_id','degree','degree_year','phone']);
        $data['is_active'] = (bool)$r->boolean('is_active');

        if ($r->hasFile('photo')) {
            if ($doctor->photo_path) Storage::disk('public')->delete($doctor->photo_path);
            $data['photo_path'] = $r->file('photo')->store('doctors','public');
        }

        $doctor->update($data);

        if ($doctor->type === 'independent') {
            $doctor->update(['university_id'=>null,'college_id'=>null,'major_id'=>null]);
            $doctor->majors()->sync($r->major_ids ?? []);
        } else {
            $doctor->majors()->sync([]); // لا شيء للمستقلين فقط
        }

        return redirect()->route('admin.doctors.index')->with('success','تم تحديث بيانات الدكتور.');
    }

    public function destroy(Doctor $doctor)
    {
        if ($doctor->photo_path) Storage::disk('public')->delete($doctor->photo_path);
        $doctor->majors()->sync([]);
        $doctor->delete();
        return back()->with('success','تم حذف الدكتور.');
    }
}
