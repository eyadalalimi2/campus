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
        if ($subjectId) {
            $links = MedicalSubjectContent::with(['subject.term.year','content'])
                ->where('subject_id', $subjectId)
                ->orderBy('sort_order')->get();
        }

        return view('admin.medical_subject_contents.index', compact('subjects','links','subjectId'));
    }

    public function store(MedicalSubjectContentRequest $request)
    {
        // التريجر سيتحقق من النطاق والنوع والحالة
        MedicalSubjectContent::updateOrCreate(
            ['subject_id' => $request->subject_id, 'content_id' => $request->content_id],
            [
                'sort_order' => $request->input('sort_order', 0),
                'is_primary' => (bool)$request->input('is_primary', 0),
                'notes'      => $request->input('notes')
            ]
        );
        return back()->with('success','تم الربط بالمحتوى');
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