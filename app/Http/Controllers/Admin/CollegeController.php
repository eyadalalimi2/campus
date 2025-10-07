<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CollegeRequest;
use App\Models\College;
use App\Models\University;
use App\Models\UniversityBranch;
use Illuminate\Http\Request;

class CollegeController extends Controller
{
    public function index(Request $r)
    {
        // نحمّل الفرع والجامعة عبر الفرع
        $q = College::with(['branch.university'])
            ->orderBy('name');

        // فلاتر اختيارية 
        if ($r->filled('university_id')) {
            $q->whereHas('branch', fn($b) => $b->where('university_id', (int) $r->input('university_id')));
        }

        if ($r->filled('branch_id')) {
            $q->where('branch_id', (int) $r->input('branch_id'));
        }

        if ($search = trim((string) $r->get('q'))) {
            $q->where('name', 'like', "%{$search}%");
        }

        if ($r->filled('is_active')) {
            $q->where('is_active', (bool) $r->boolean('is_active'));
        }

        $colleges     = $q->paginate(15)->withQueryString();

        // لتحضير الفلاتر في الواجهة
        $universities = University::orderBy('name')->get();

        // إن تم اختيار جامعة، نحضّر فروعها للقائمة المنسدلة
        $branches = collect();
        if ($r->filled('university_id')) {
            $branches = UniversityBranch::where('university_id', (int) $r->input('university_id'))
                ->orderBy('name')
                ->get();
        }

        return view('admin.colleges.index', compact('colleges', 'universities', 'branches'));
    }

    public function create(Request $r)
    {
        $universities = University::orderBy('name')->get();

        // تحديد الجامعة المختارة من old أو request
        $selectedUniversityId = $r->old('university_id') ?? $r->input('university_id');

        if ($selectedUniversityId) {
            $branches = UniversityBranch::where('university_id', (int) $selectedUniversityId)
                ->orderBy('name')
                ->get();
        } else {
            $branches = UniversityBranch::orderBy('name')->get();
        }

        return view('admin.colleges.create', compact('universities', 'branches'));
    }

    public function store(CollegeRequest $r)
    {
        $data = $r->validated();
        $data['is_active'] = $r->boolean('is_active');

        // College أصبحت مرتبطة بالفرع مباشرة
        // CollegeRequest يجب أن يتحقق أن branch_id موجود وصحيح ويتبع الجامعة المختارة (إن تم تمريرها)
        College::create($data);

        return redirect()
            ->route('admin.colleges.index')
            ->with('success', 'تم إنشاء الكلية.');
    }

    public function edit(College $college)
    {
        $college->load('branch.university');

        $universities = University::orderBy('name')->get();

        // نحضّر فروع الجامعة التابعة لهذه الكلية لعرضها في القائمة
        $branches = collect();
        $universityId = $college->branch?->university_id;
        if ($universityId) {
            $branches = UniversityBranch::where('university_id', $universityId)
                ->orderBy('name')
                ->get();
        }

        return view('admin.colleges.edit', compact('college', 'universities', 'branches'));
    }

    public function update(CollegeRequest $r, College $college)
    {
        $data = $r->validated();
        $data['is_active'] = $r->boolean('is_active');

        $college->update($data);

        return redirect()
            ->route('admin.colleges.index')
            ->with('success', 'تم تحديث الكلية.');
    }

    public function destroy(College $college)
    {
        $college->delete();

        return back()->with('success', 'تم حذف الكلية.');
    }
}
