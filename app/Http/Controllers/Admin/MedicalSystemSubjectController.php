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
        if ($systemId) {
            $links = MedicalSystemSubject::with(['system.year','subject.term.year','subject.medSubject'])
                ->where('system_id', $systemId)->get();
        }

        return view('admin.medical_system_subjects.index', compact('systems','links','systemId'));
    }

    public function store(MedicalSystemSubjectRequest $request)
    {
        MedicalSystemSubject::firstOrCreate($request->validated());
        return back()->with('success','تم الربط');
    }

    public function destroy(MedicalSystemSubject $medical_system_subject)
    {
        $medical_system_subject->delete();
        return back()->with('success','تم فك الربط');
    }
}