<?php

namespace App\Http\Controllers;

use App\Models\MedicalYear;
use App\Models\MedicalTerm;
use App\Models\MedicalSystem;
use Illuminate\Http\Request;

class MedicalSystemController extends Controller
{
    // signature يستفيد من ربط المصطلحات المخصص أعلاه
    public function systemsByYearAndTerm(int $year, MedicalTerm $term)
    {
        // تأكيد التوافق: الربط المخصص ضمنيًا يضمن year_id
        if ((int)$term->year_id !== (int)$year) {
            return response()->json([
                'status' => 'error',
                'message' => 'السنة والفصل غير متطابقين.'
            ], 404);
        }

        $systems = MedicalSystem::query()
            ->where('year_id', $year)
            ->where(function ($q) use ($term) {
                // دعم أنظمة عامة على مستوى السنة (term_id NULL) + الخاصة بالفصل
                $q->whereNull('term_id')->orWhere('term_id', $term->id);
            })
            ->where('is_active', 1)
            ->orderBy('sort_order')->orderBy('id')
            ->get();

        return response()->json([
            'status' => 'success',
            'data'   => $systems
        ]);
    }
}
