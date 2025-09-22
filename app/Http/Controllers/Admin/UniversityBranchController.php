<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\University;
use App\Models\UniversityBranch;
use App\Models\College;
use Illuminate\Http\Request;

class UniversityBranchController extends Controller
{
    public function index(Request $r)
    {
        $filters = [
            'q'             => trim((string) $r->get('q', '')),
            'university_id' => $r->integer('university_id') ?: null,
            'is_active'     => $r->filled('is_active') ? (int) $r->boolean('is_active') : null,
        ];

        $q = UniversityBranch::query()
            ->with('university')
            ->orderBy('name');

        if ($filters['q']) {
            $term = $filters['q'];
            $q->where(function ($w) use ($term) {
                $w->where('name', 'like', "%{$term}%")
                  ->orWhere('address', 'like', "%{$term}%")
                  ->orWhere('phone', 'like', "%{$term}%")
                  ->orWhere('email', 'like', "%{$term}%");
            });
        }

        if ($filters['university_id']) {
            $q->where('university_id', $filters['university_id']);
        }

        if (!is_null($filters['is_active'])) {
            $q->where('is_active', (bool) $filters['is_active']);
        }

        $branches = $q->paginate(15)->withQueryString();
        $universities = University::orderBy('name')->get();

        return view('admin.branches.index', compact('branches', 'universities', 'filters'));
    }

    public function create(Request $r)
    {
        $universities = University::orderBy('name')->get();

        // نمرّر university_id مسبقًا إن جاء من رابط مفلتر
        $selectedUniversityId = $r->integer('university_id') ?: null;

        return view('admin.branches.create', compact('universities', 'selectedUniversityId'));
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'university_id' => ['required', 'exists:universities,id'],
            'name'          => ['required', 'string', 'max:255'],
            'address'       => ['nullable', 'string', 'max:500'],
            'phone'         => ['nullable', 'string', 'max:50'],
            'email'         => ['nullable', 'email', 'max:255'],
            'is_active'     => ['nullable', 'boolean'],
        ], [], [
            'university_id' => 'الجامعة',
            'name'          => 'اسم الفرع',
            'address'       => 'العنوان',
            'phone'         => 'الهاتف',
            'email'         => 'البريد الإلكتروني',
            'is_active'     => 'الحالة',
        ]);

        $data['is_active'] = (bool) $r->boolean('is_active');

        UniversityBranch::create($data);

        return redirect()
            ->route('admin.branches.index', ['university_id' => $data['university_id']])
            ->with('success', 'تم إنشاء الفرع بنجاح.');
    }

    public function edit(UniversityBranch $branch)
    {
        $branch->load('university');
        $universities = University::orderBy('name')->get();

        return view('admin.branches.edit', compact('branch', 'universities'));
    }

    public function update(Request $r, UniversityBranch $branch)
    {
        $data = $r->validate([
            'university_id' => ['required', 'exists:universities,id'],
            'name'          => ['required', 'string', 'max:255'],
            'address'       => ['nullable', 'string', 'max:500'],
            'phone'         => ['nullable', 'string', 'max:50'],
            'email'         => ['nullable', 'email', 'max:255'],
            'is_active'     => ['nullable', 'boolean'],
        ], [], [
            'university_id' => 'الجامعة',
            'name'          => 'اسم الفرع',
            'address'       => 'العنوان',
            'phone'         => 'الهاتف',
            'email'         => 'البريد الإلكتروني',
            'is_active'     => 'الحالة',
        ]);

        $data['is_active'] = (bool) $r->boolean('is_active');

        $branch->update($data);

        return redirect()
            ->route('admin.branches.index', ['university_id' => $data['university_id']])
            ->with('success', 'تم تحديث بيانات الفرع.');
    }

    public function destroy(UniversityBranch $branch)
    {
        // منع الحذف إن كانت هناك كليات مرتبطة بالفرع
        $hasColleges = College::where('branch_id', $branch->id)->exists();
        if ($hasColleges) {
            return back()->with('error', 'لا يمكن حذف الفرع لوجود كليات مرتبطة به. يرجى نقل أو حذف الكليات أولاً.');
        }

        $branch->delete();

        return back()->with('success', 'تم حذف الفرع.');
    }
}
