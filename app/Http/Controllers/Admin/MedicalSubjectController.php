<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MedicalSubjectRequest;
use App\Models\MedicalSubject;
use App\Models\MedicalTerm;
use Illuminate\Database\QueryException;

class MedicalSubjectController extends Controller
{
    public function index()
    {
        $subjects = MedicalSubject::with(['term.year.major', 'medSubject'])
            ->orderBy('term_id')
            ->orderBy('sort_order')
            ->paginate(20);

        return view('admin.medical_subjects.index', compact('subjects'));
    }

    public function create()
    {
        $terms = MedicalTerm::with('year.major')->get()->mapWithKeys(function ($t) {
            return [
                $t->id => $t->year->major->name . ' - سنة ' . $t->year->year_number . ' - ترم ' . $t->term_number
            ];
        });

        // من جدول عام med_subjects (لا نعدل ملفاته)
        $medSubjects = \App\Models\MedSubject::orderBy('name')->pluck('name', 'id');

        return view('admin.medical_subjects.create', compact('terms', 'medSubjects'));
    }

    public function store(MedicalSubjectRequest $request)
    {
        $data = $request->validated();

        // قيَم افتراضية آمنة
        $data['is_active']     = array_key_exists('is_active', $data) ? (int) !!$data['is_active'] : 1;
        $data['display_name']  = $data['display_name'] ?? null;

        // لو لم يأتِ sort_order من النموذج، احسب التالي داخل نفس الـ term
        if (!array_key_exists('sort_order', $data) || $data['sort_order'] === null || $data['sort_order'] === '') {
            $max = MedicalSubject::where('term_id', $data['term_id'])->max('sort_order');
            $data['sort_order'] = is_null($max) ? 0 : ($max + 1);
        }

        // معالجة رفع الصورة أو أخذ صورة المادة العامة
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $path = $file->store('medical_subjects', 'public');
            $data['image'] = $path;
        } elseif (empty($data['image']) && !empty($data['med_subject_id'])) {
            $base = \App\Models\MedSubject::find($data['med_subject_id']);
            if ($base) {
                $data['image'] = $base->image_path;
            }
        }

        try {
            MedicalSubject::create($data);
        } catch (QueryException $e) {
            // معالجة لطيفة لحالة التكرار على (term_id, med_subject_id)
            if ($e->getCode() === '23000') {
                return back()
                    ->withInput()
                    ->withErrors(['med_subject_id' => 'هذه المادة العامة مضافة بالفعل لهذا الترم.']);
            }
            throw $e;
        }

        return redirect()
            ->route('admin.medical_subjects.index')
            ->with('success', 'تم الإنشاء');
    }

    public function edit(MedicalSubject $medical_subject)
    {
        $terms = MedicalTerm::with('year.major')->get()->mapWithKeys(function ($t) {
            return [
                $t->id => $t->year->major->name . ' - سنة ' . $t->year->year_number . ' - ترم ' . $t->term_number
            ];
        });

        $medSubjects = \App\Models\MedSubject::orderBy('name')->pluck('name', 'id');

        return view('admin.medical_subjects.edit', [
            'subject'     => $medical_subject,
            'terms'       => $terms,
            'medSubjects' => $medSubjects,
        ]);
    }

    public function update(MedicalSubjectRequest $request, MedicalSubject $medical_subject)
    {
        $data = $request->validated();

        // تثبيت القِيَم الافتراضية
        if (!array_key_exists('is_active', $data)) {
            $data['is_active'] = $medical_subject->is_active; // لا تغيّر إن لم يُرسل
        } else {
            $data['is_active'] = (int) !!$data['is_active'];
        }

        if (!array_key_exists('sort_order', $data) || $data['sort_order'] === null || $data['sort_order'] === '') {
            // إن لم يُرسل في التعديل، اتركه كما هو
            $data['sort_order'] = $medical_subject->sort_order;
        }

        // معالجة رفع الصورة أو أخذ صورة المادة العامة
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $path = $file->store('medical_subjects', 'public');
            $data['image'] = $path;
        } elseif (empty($data['image']) && !empty($data['med_subject_id'])) {
            $base = \App\Models\MedSubject::find($data['med_subject_id']);
            if ($base) {
                $data['image'] = $base->image_path;
            }
        }

        try {
            $medical_subject->update($data);
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return back()
                    ->withInput()
                    ->withErrors(['med_subject_id' => 'هذه المادة العامة مضافة بالفعل لهذا الترم.']);
            }
            throw $e;
        }

        return redirect()
            ->route('admin.medical_subjects.index')
            ->with('success', 'تم التحديث');
    }

    public function destroy(MedicalSubject $medical_subject)
    {
        $medical_subject->delete();
        return back()->with('success', 'تم الحذف');
    }
}