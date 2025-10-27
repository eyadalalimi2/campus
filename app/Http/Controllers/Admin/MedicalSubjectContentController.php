<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MedicalSubjectContentRequest;
use App\Models\MedicalSubject;
use App\Models\MedicalSubjectContent;
use App\Models\Content;
use Illuminate\Http\Request;

class MedicalSubjectContentController extends Controller
{
    public function index(Request $request)
    {
        $subjectId = $request->get('subject_id');
        $subjects = MedicalSubject::with(['term.year.major','medSubject'])->orderBy('term_id')->get();

        $links = collect();
        $contents = collect();
        if ($subjectId) {
            $links = MedicalSubjectContent::with(['subject.term.year','content'])
                ->where('subject_id', $subjectId)
                ->orderBy('sort_order')->get();

            // determine subject's university id (via term->year->major)
            $subject = MedicalSubject::with('term.year.major.college.branch')->find($subjectId);
            $universityId = null;
            if ($subject && $subject->term && $subject->term->year && $subject->term->year->major) {
                $major = $subject->term->year->major;
                $universityId = $major->university_id ?? null;
            }

            // load eligible contents (published + active + file/link) and exclude already linked
            $linked = $links->pluck('content_id')->toArray();
            $contents = Content::query()
                ->where('status','published')
                ->where('is_active',1)
                ->whereIn('type',['file','link'])
                ->when($universityId, function($q) use ($universityId){
                    $q->where('university_id', $universityId);
                })
                ->when(count($linked), function($q) use ($linked){
                    $q->whereNotIn('id',$linked);
                })
                ->orderByDesc('published_at')
                ->limit(500)
                ->get();
        }

        return view('admin.medical_subject_contents.index', compact('subjects','links','subjectId','contents'));
    }

    public function store(MedicalSubjectContentRequest $request)
    {
        $data = $request->validated();
        $subjectId = $data['subject_id'];

        // handle multiple content_ids
        if (!empty($data['content_ids']) && is_array($data['content_ids'])) {
            // Detect created vs skipped by checking existence before loop
            $created = 0; $skipped = 0;
            // determine subject's university id (ensure we skip mismatched contents)
            $subject = MedicalSubject::with('term.year.major.college.branch')->find($subjectId);
            $universityId = null;
            if ($subject && $subject->term && $subject->term->year && $subject->term->year->major) {
                $universityId = $subject->term->year->major->university_id ?? null;
            }

            foreach ($data['content_ids'] as $cid) {
                $already = MedicalSubjectContent::where('subject_id',$subjectId)->where('content_id',$cid)->exists();
                if ($already) { $skipped++; continue; }

                $content = Content::find($cid);
                if (!$content) { $skipped++; continue; }
                // skip if content's university mismatches subject's university
                if ($universityId && $content->university_id != $universityId) {
                    $skipped++;
                    continue;
                }

                MedicalSubjectContent::create([
                    'subject_id' => $subjectId,
                    'content_id' => $cid,
                    'sort_order' => $request->input('sort_order', 0),
                    'is_primary' => (bool)$request->input('is_primary', 0),
                    'notes'      => $request->input('notes')
                ]);
                $created++;
            }

            $parts = [];
            $parts[] = "تم ربط {$created} محتوى";
            if ($skipped) $parts[] = "وتخطى {$skipped} محتوى لأنها مرتبطة سابقًا";
            return back()->with('success', implode('، ', $parts));
        }

        // fallback to single content_id
        if (!empty($data['content_id'])) {
            $exists = MedicalSubjectContent::where('subject_id',$data['subject_id'])->where('content_id',$data['content_id'])->exists();
            if ($exists) {
                return back()->with('success','تم تخطي المحتوى لأنه مرتبط سابقًا');
            }

            $subject = MedicalSubject::with('term.year.major.college.branch')->find($data['subject_id']);
            $universityId = null;
            if ($subject && $subject->term && $subject->term->year && $subject->term->year->major) {
                $universityId = $subject->term->year->major->university_id ?? null;
            }

            $content = Content::find($data['content_id']);
            if ($universityId && $content && $content->university_id != $universityId) {
                return back()->with('success','تم تخطي المحتوى لعدم تطابق الجامعة');
            }

            MedicalSubjectContent::create([
                'subject_id' => $data['subject_id'],
                'content_id' => $data['content_id'],
                'sort_order' => $request->input('sort_order', 0),
                'is_primary' => (bool)$request->input('is_primary', 0),
                'notes'      => $request->input('notes')
            ]);
            return back()->with('success','تم ربط 1 محتوى');
        }

        return back()->withErrors(['content_id' => 'لم يتم اختيار أي محتوى']);
    }

    public function destroy(MedicalSubjectContent $medical_subject_content)
    {
        $medical_subject_content->delete();
        return back()->with('success','تم الحذف');
    }

    // مساعد لجلب قائمة المحتويات القابلة للربط (Published + Active + File/Link)
    public function searchEligibleContents(Request $request)
    {
        $q = trim((string)$request->get('q',''));
        $builder = Content::query()
            ->where('status','published')
            ->where('is_active',1)
            ->whereIn('type',['file','link']);

        if ($q !== '') {
            $builder->where(function($w) use ($q){
                $w->where('title','like',"%$q%")
                  ->orWhere('description','like',"%$q%");
            });
        }

        return $builder->orderByDesc('published_at')->limit(30)->get(['id','title','type','published_at']);
    }
}