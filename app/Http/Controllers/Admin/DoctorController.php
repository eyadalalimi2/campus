<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\University;
use App\Models\UniversityBranch;
use App\Models\College;
use App\Models\Major;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class DoctorController extends Controller
{
    public function index(Request $r)
    {
        $q = Doctor::query()
            ->with(['university','branch','college','major','majors'])
            ->orderBy('name');

        // فلاتر
        if ($r->filled('type'))         $q->where('type', $r->type);
        if ($r->filled('university_id')) $q->where('university_id', (int)$r->university_id);
        if ($r->filled('branch_id'))     $q->where('branch_id', (int)$r->branch_id);
        if ($r->filled('college_id'))    $q->where('college_id', (int)$r->college_id);

        if ($r->filled('major_id')) {
            $majorId = (int)$r->major_id;
            // للجامعي: على العمود المباشر major_id
            // للمستقل: عبر Pivot majors()
            $q->where(function ($w) use ($majorId) {
                $w->where('major_id', $majorId)
                  ->orWhereHas('majors', fn($m) => $m->whereKey($majorId));
            });
        }

        if ($search = trim((string)$r->get('q'))) {
            $q->where('name','like',"%{$search}%");
        }

        $doctors = $q->paginate(15)->withQueryString();

        // مصادر القوائم حسب الهرم
        $universities = University::orderBy('name')->get();

        $branches = collect();
        if ($r->filled('university_id')) {
            $branches = UniversityBranch::where('university_id', (int)$r->university_id)
                ->orderBy('name')->get();
        }

        $colleges = collect();
        if ($r->filled('branch_id')) {
            $colleges = College::where('branch_id', (int)$r->branch_id)
                ->orderBy('name')->get();
        }

        $majors = collect();
        if ($r->filled('college_id')) {
            $majors = Major::where('college_id', (int)$r->college_id)
                ->orderBy('name')->get();
        }

        return view('admin.doctors.index', compact(
            'doctors','universities','branches','colleges','majors'
        ));
    }

    public function create(Request $r)
    {
        $universities = University::orderBy('name')->get();

        $branches = collect();
        if ($r->filled('university_id')) {
            $branches = UniversityBranch::where('university_id', (int)$r->university_id)
                ->orderBy('name')->get();
        }

        $colleges = collect();
        if ($r->filled('branch_id')) {
            $colleges = College::where('branch_id', (int)$r->branch_id)
                ->orderBy('name')->get();
        }

        $majors = collect();
        if ($r->filled('college_id')) {
            $majors = Major::where('college_id', (int)$r->college_id)
                ->orderBy('name')->get();
        }

        return view('admin.doctors.create', compact('universities','branches','colleges','majors'));
    }

    public function store(Request $r)
    {
        $r->validate([
            'name'  => ['required','string','max:200'],
            'type'  => ['required', Rule::in(['university','independent'])],

            // جامعي
            'university_id' => ['nullable','required_if:type,university','exists:universities,id'],
            'branch_id'     => ['nullable','required_if:type,university','exists:university_branches,id'],
            'college_id'    => ['nullable','required_if:type,university','exists:colleges,id'],
            'major_id'      => ['nullable','required_if:type,university','exists:majors,id'],

            // مستقل
            'major_ids'     => ['nullable','array'],
            'major_ids.*'   => ['integer','exists:majors,id'],

            // عامة
            'degree'        => ['nullable','string','max:150'],
            'degree_year'   => ['nullable','digits:4','integer','min:1900','max:'.date('Y')],
            'phone'         => ['nullable','string','max:30'],
            'photo'         => ['nullable','image','max:2048'],
            'is_active'     => ['nullable','boolean'],
        ], [], [
            'branch_id' => 'الفرع',
        ]);

        // تحقق هرمي إضافي
        $this->validateHierarchy($r);

        $data = $r->only([
            'name','type','university_id','branch_id','college_id','major_id','degree','degree_year','phone'
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
        $doctor->load(['university','branch','college','major','majors']);

        $universities = University::orderBy('name')->get();

        $branches = collect();
        if ($doctor->university_id) {
            $branches = UniversityBranch::where('university_id', $doctor->university_id)
                ->orderBy('name')->get();
        }

        $colleges = collect();
        if ($doctor->branch_id) {
            $colleges = College::where('branch_id', $doctor->branch_id)
                ->orderBy('name')->get();
        }

        $majors = collect();
        if ($doctor->college_id) {
            $majors = Major::where('college_id', $doctor->college_id)
                ->orderBy('name')->get();
        }

        $selectedMajors = $doctor->majors()->pluck('majors.id')->toArray();

        return view('admin.doctors.edit', compact(
            'doctor','universities','branches','colleges','majors','selectedMajors'
        ));
    }

    public function update(Request $r, Doctor $doctor)
    {
        $r->validate([
            'name'  => ['required','string','max:200'],
            'type'  => ['required', Rule::in(['university','independent'])],

            'university_id' => ['nullable','required_if:type,university','exists:universities,id'],
            'branch_id'     => ['nullable','required_if:type,university','exists:university_branches,id'],
            'college_id'    => ['nullable','required_if:type,university','exists:colleges,id'],
            'major_id'      => ['nullable','required_if:type,university','exists:majors,id'],

            'major_ids'     => ['nullable','array'],
            'major_ids.*'   => ['integer','exists:majors,id'],

            'degree'        => ['nullable','string','max:150'],
            'degree_year'   => ['nullable','digits:4','integer','min:1900','max:'.date('Y')],
            'phone'         => ['nullable','string','max:30'],
            'photo'         => ['nullable','image','max:2048'],
            'is_active'     => ['nullable','boolean'],
        ], [], [
            'branch_id' => 'الفرع',
        ]);

        // تحقق هرمي إضافي
        $this->validateHierarchy($r);

        $data = $r->only(['name','type','university_id','branch_id','college_id','major_id','degree','degree_year','phone']);
        $data['is_active'] = (bool)$r->boolean('is_active');

        if ($r->hasFile('photo')) {
            if ($doctor->photo_path) {
                Storage::disk('public')->delete($doctor->photo_path);
            }
            $data['photo_path'] = $r->file('photo')->store('doctors','public');
        }

        $doctor->update($data);

        if ($doctor->type === 'independent') {
            // مستقل: ننظف الأعمدة المؤسسية ونربط عبر pivot
            $doctor->update(['university_id'=>null,'branch_id'=>null,'college_id'=>null,'major_id'=>null]);
            $doctor->majors()->sync($r->major_ids ?? []);
        } else {
            // جامعي: لا حاجة لأي pivot إضافي
            $doctor->majors()->sync([]);
        }

        return redirect()->route('admin.doctors.index')->with('success','تم تحديث بيانات الدكتور.');
    }

    public function destroy(Doctor $doctor)
    {
        if ($doctor->photo_path) {
            Storage::disk('public')->delete($doctor->photo_path);
        }
        $doctor->majors()->sync([]);
        $doctor->delete();

        return back()->with('success','تم حذف الدكتور.');
    }

    /**
     * تحقّق هرمي: فرع ← جامعة، كلية ← فرع، تخصص ← كلية.
     */
    private function validateHierarchy(Request $r): void
    {
        if ($r->input('type') !== 'university') {
            return; // لا حاجة للتحقق الهرمي للمستقلين
        }

        $universityId = $r->integer('university_id') ?: null;
        $branchId     = $r->integer('branch_id') ?: null;
        $collegeId    = $r->integer('college_id') ?: null;
        $majorId      = $r->integer('major_id') ?: null;

        // 1) الفرع يتبع الجامعة
        if ($universityId && $branchId) {
            $ok = UniversityBranch::where('id', $branchId)
                ->where('university_id', $universityId)
                ->exists();
            if (! $ok) {
                abort(422, 'الفرع المحدد لا يتبع الجامعة المختارة.');
            }
        }

        // 2) الكلية تتبع الفرع
        if ($collegeId) {
            $branchOfCollege = College::whereKey($collegeId)->value('branch_id');
            if (! $branchOfCollege) {
                abort(422, 'الكلية المحددة غير موجودة.');
            }
            if ($branchId && (int)$branchOfCollege !== (int)$branchId) {
                abort(422, 'الكلية لا تتبع الفرع المختار.');
            }
        }

        // 3) التخصص يتبع الكلية
        if ($majorId) {
            $collegeOfMajor = Major::whereKey($majorId)->value('college_id');
            if (! $collegeOfMajor) {
                abort(422, 'التخصص المحدد غير موجود.');
            }
            if ($collegeId && (int)$collegeOfMajor !== (int)$collegeId) {
                abort(422, 'التخصص لا يتبع الكلية المختارة.');
            }
        }
    }
}
