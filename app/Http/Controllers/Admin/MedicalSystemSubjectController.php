<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MedicalSystemSubjectRequest;
use App\Models\MedicalSystem;
use App\Models\MedicalSystemSubject;
use App\Models\MedicalSubject;
use Illuminate\Http\Request;

class MedicalSystemSubjectController extends Controller
{
    public function index(Request $request)
    {
        $systemId = $request->get('system_id');
        $systems = MedicalSystem::with('year')->orderBy('year_id')->get();

        $links = collect();
    $subjects = collect();
        if ($systemId) {
            $links = MedicalSystemSubject::with(['system.year','subject.term.year','subject.medSubject'])
                ->where('system_id', $systemId)->get();
            // load available subjects for this system's year (exclude already linked)
            $system = MedicalSystem::find($systemId);
            $linkedIds = $links->pluck('subject_id')->toArray();
            $subjects = MedicalSubject::with('term.year','medSubject')
                ->whereHas('term', function($q) use ($system) {
                    if ($system && $system->year_id) {
                        $q->where('year_id', $system->year_id);
                    }
                })
                ->when(count($linkedIds), function($q) use ($linkedIds) {
                    $q->whereNotIn('id', $linkedIds);
                })
                ->orderBy('term_id')
                ->orderBy('id')
                ->get();
        }

        return view('admin.medical_system_subjects.index', compact('systems','links','systemId','subjects'));
    }

    public function store(MedicalSystemSubjectRequest $request)
    {
        $data = $request->validated();

        $systemId = $data['system_id'];

        // handle multiple subject_ids
        if (!empty($data['subject_ids']) && is_array($data['subject_ids'])) {
            $created = 0;
            $skipped = 0;
            foreach ($data['subject_ids'] as $sid) {
                $m = MedicalSystemSubject::firstOrCreate([
                    'system_id' => $systemId,
                    'subject_id' => $sid,
                ]);
                if ($m->wasRecentlyCreated) {
                    $created++;
                } else {
                    $skipped++;
                }
            }

            $parts = [];
            $parts[] = "تم ربط {$created} مادة";
            if ($skipped) $parts[] = "وتخطى {$skipped} مادة لأنها مرتبطة سابقًا";
            return back()->with('success', implode('، ', $parts));
        }

        // fallback to single subject_id
        if (!empty($data['subject_id'])) {
            $m = MedicalSystemSubject::firstOrCreate([
                'system_id' => $systemId,
                'subject_id' => $data['subject_id'],
            ]);
            if ($m->wasRecentlyCreated) {
                return back()->with('success','تم ربط 1 مادة');
            }
            return back()->with('success','تم تخطي المادة لأنها مرتبطة سابقًا');
        }

        return back()->withErrors(['subject_id' => 'لم يتم اختيار أي مادة']);
    }

    public function destroy(MedicalSystemSubject $medical_system_subject)
    {
        $medical_system_subject->delete();
        return back()->with('success','تم فك الربط');
    }
}