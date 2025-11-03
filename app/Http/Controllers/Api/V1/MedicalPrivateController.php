<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\ContentResource;
use App\Http\Resources\MedicalYearResource;
use App\Http\Resources\MedicalTermResource;
use App\Http\Resources\MedicalSubjectResource;
use App\Http\Resources\MedicalSystemResource;

class MedicalPrivateController extends Controller
{
    /**
     * GET /medical/years?major_id=
     * يرجع السنوات الخاصة بتخصص محدد (أو تخصص المستخدم إن لم يُمرر)
     */
    public function years(Request $request)
    {
        $user = $request->user();

        $majorId = (int)($request->query('major_id') ?: ($user->major_id ?? 0));
        if (!$majorId) {
            return response()->json(['message' => 'major_id is required or user must have a major'], 422);
        }

        // حصر على تخصص المستخدم نفسه للأمان (إن مرّر تخصص مختلف نرفض)
        if ($user->major_id && $user->major_id != $majorId) {
            return response()->json(['message' => 'Forbidden: major mismatch'], 403);
        }

        $rows = DB::table('MedicalYears as my')
            ->select('my.id', 'my.major_id', 'my.year_number', 'my.is_active', 'my.sort_order', 'my.image_path')
            ->where('my.major_id', $majorId)
            ->where('my.is_active', 1)
            ->orderBy('my.sort_order')
            ->orderBy('my.year_number')
            ->get();

        return MedicalYearResource::collection($rows);
    }

    /**
     * GET /medical/years/{year}/terms
     */
    public function terms(Request $request, $yearId)
    {
        $user = $request->user();

        // تأكيد أن السنة تعود لتخصص المستخدم
        $year = DB::table('MedicalYears')->where('id', $yearId)->first();
        if (!$year) return response()->json(['message' => 'Year not found'], 404);

        if ($user->major_id && $year->major_id != $user->major_id) {
            return response()->json(['message' => 'Forbidden: major mismatch'], 403);
        }

        $terms = DB::table('MedicalTerms as mt')
            ->select('mt.id', 'mt.year_id', 'mt.term_number', 'mt.is_active', 'mt.sort_order', 'mt.image_path')
            ->where('mt.year_id', $yearId)
            ->where('mt.is_active', 1)
            ->orderBy('mt.sort_order')
            ->orderBy('mt.term_number')
            ->get();

        return MedicalTermResource::collection($terms);
    }

    /**
     * GET /medical/terms/{term}/subjects?track=
     * يرجع مواد الفصل، اختياريًا بفلترة track
     */
    public function subjects(Request $request, $termId)
    {
        $user = $request->user();
        $track = $request->query('track'); // REQUIRED|SYSTEM|CLINICAL|null

        // جلب year_id لتأكيد التخصص
        $term = DB::table('MedicalTerms')->where('id', $termId)->first();
        if (!$term) return response()->json(['message' => 'Term not found'], 404);

        $year = DB::table('MedicalYears')->where('id', $term->year_id)->first();
        if (!$year) return response()->json(['message' => 'Year not found'], 404);

        if ($user->major_id && $year->major_id != $user->major_id) {
            return response()->json(['message' => 'Forbidden: major mismatch'], 403);
        }

        $q = DB::table('MedicalSubjects as ms')
            ->join('med_subjects as base', 'base.id', '=', 'ms.med_subject_id')
            ->select(
                'ms.id',
                'ms.term_id',
                'ms.med_subject_id',
                'ms.track',
                'ms.display_name',
                'ms.image as image',
                'ms.is_active',
                'ms.sort_order',
                'base.name as base_name',
                'base.image_path as base_image',
                'base.scope as base_scope',
                'base.slug as base_slug'
            )
            ->where('ms.term_id', $termId)
            ->where('ms.is_active', 1);

        if ($track) {
            $q->where('ms.track', $track);
        }

        $rows = $q->orderBy('ms.sort_order')->orderBy('base.name')->get();

        return MedicalSubjectResource::collection($rows);
    }

    /**
     * GET /medical/systems?year_id=
     * يرجع الأنظمة في سنة محددة
     */
    public function systems(Request $request)
    {
        $user   = $request->user();
        $yearId = (int) $request->query('year_id');

        if (!$yearId) {
            return response()->json(['message' => 'year_id is required'], 422);
        }

        $year = DB::table('MedicalYears')->where('id', $yearId)->first();
        if (!$year) return response()->json(['message' => 'Year not found'], 404);

        if ($user->major_id && $year->major_id != $user->major_id) {
            return response()->json(['message' => 'Forbidden: major mismatch'], 403);
        }

        $rows = DB::table('MedicalSystems as sys')
            ->join('med_devices as d', 'd.id', '=', 'sys.med_device_id')
            ->select(
                'sys.id',
                'sys.year_id',
                'sys.med_device_id',
                'sys.display_name',
                'sys.is_active',
                'sys.sort_order',
                'd.name as device_name',
                'd.slug as device_slug',
                'd.image_path as device_image'
            )
            ->where('sys.year_id', $yearId)
            ->where('sys.is_active', 1)
            ->orderBy('sys.sort_order')
            ->orderBy('d.name')
            ->get();

        return MedicalSystemResource::collection($rows);
    }
    public function systemsByTerm(\Illuminate\Http\Request $request, $termId, $year = null)
    {
        $user = $request->user();
        $yearIdFromPath = is_numeric($year) ? (int)$year : null;

        // تأكيد وجود الترم
        $term = DB::table('MedicalTerms')->where('id', $termId)->first();
        if (!$term) {
            return response()->json(['status' => 'error', 'message' => 'المورد غير موجود.'], 404);
        }

        // لو مسار السنة موجود، تأكد التطابق مع الترم
        if ($yearIdFromPath !== null && (int)$term->year_id !== $yearIdFromPath) {
            return response()->json(['status' => 'error', 'message' => 'المورد غير موجود.'], 404);
        }

        // جلب السنة للتحقق من تخصّص المستخدم
        $yearRow = DB::table('MedicalYears')->where('id', $term->year_id)->first();
        if (!$yearRow) {
            return response()->json(['status' => 'error', 'message' => 'المورد غير موجود.'], 404);
        }

        // تأمين التخصّص
        if ($user?->major_id && (int)$yearRow->major_id !== (int)$user->major_id) {
            return response()->json(['status' => 'error', 'message' => 'غير مسموح: عدم تطابق التخصص'], 403);
        }

        // جلب الأنظمة المقيدة بالترم
        $rows = DB::table('MedicalSystems as sys')
            ->join('med_devices as d', 'd.id', '=', 'sys.med_device_id')
            ->select(
                'sys.id',
                'sys.year_id',
                'sys.term_id',
                'sys.med_device_id',
                'sys.display_name',
                'sys.is_active',
                'sys.sort_order',
                'd.name as device_name',
                'd.slug as device_slug',
                'd.image_path as device_image'
            )
            ->where('sys.term_id', $termId)
            ->where('sys.is_active', 1)
            ->orderBy('sys.sort_order')
            ->orderBy('d.name')
            ->get();

        return \App\Http\Resources\MedicalSystemResource::collection($rows);
    }


    /**
     * GET /medical/systems/{system}/subjects
     * يرجع مواد النظام (من جدول MedicalSystemSubjects)
     */
    public function systemSubjects(Request $request, $systemId)
    {
        $user = $request->user();

        $sys = DB::table('MedicalSystems')->where('id', $systemId)->first();
        if (!$sys) return response()->json(['message' => 'System not found'], 404);

        $year = DB::table('MedicalYears')->where('id', $sys->year_id)->first();
        if (!$year) return response()->json(['message' => 'Year not found'], 404);

        if ($user->major_id && $year->major_id != $user->major_id) {
            return response()->json(['message' => 'Forbidden: major mismatch'], 403);
        }

        $rows = DB::table('MedicalSystemSubjects as link')
            ->join('MedicalSubjects as ms', 'ms.id', '=', 'link.subject_id')
            ->join('med_subjects as base', 'base.id', '=', 'ms.med_subject_id')
            ->select(
                'ms.id',
                'ms.term_id',
                'ms.med_subject_id',
                'ms.track',
                'ms.display_name',
                'ms.image as image',
                'ms.is_active',
                'ms.sort_order',
                'base.name as base_name',
                'base.image_path as base_image',
                'base.slug as base_slug'
            )
            ->where('link.system_id', $systemId)
            ->where('ms.is_active', 1)
            ->orderBy('ms.sort_order')
            ->orderBy('base.name')
            ->get();

        return MedicalSubjectResource::collection($rows);
    }

    /**
     * GET /medical/subjects/{subject}/contents?type=file|link
     * يرجع ملفات/روابط المادة الخاصة (من contents عبر MedicalSubjectContent)
     * مع مطابقة مسار المستخدم (university/branch/college/major) وحالة النشر.
     */
    public function subjectContents(Request $request, $subjectId)
    {
        $user = $request->user();
        $type = $request->query('type'); // اختياري

        // التحقق من أن المادة تتبع نفس الجامعة/الفرع/الكلية/التخصص
        $ctx = DB::table('vw_medical_subject_context')->where('medical_subject_id', $subjectId)->first();
        if (!$ctx) return response()->json(['message' => 'Subject not found'], 404);

        // تطابق المسار مع المستخدم
        if (
            ($user->university_id && $user->university_id != $ctx->university_id) ||
            ($user->branch_id     && $user->branch_id     != $ctx->branch_id)     ||
            ($user->college_id    && $user->college_id    != $ctx->college_id)    ||
            ($user->major_id      && $user->major_id      != $ctx->major_id)
        ) {
            return response()->json(['message' => 'Forbidden: audience mismatch'], 403);
        }

        $q = DB::table('MedicalSubjectContent as lnk')
            ->join('contents as c', 'c.id', '=', 'lnk.content_id')
            ->select(
                'c.id',
                'c.title',
                'c.description',
                'c.type',
                'c.source_url',
                'c.file_path',
                'c.university_id',
                'c.branch_id',
                'c.college_id',
                'c.major_id',
                'c.status',
                'c.is_active',
                'c.published_at',
                'c.version',
                'lnk.sort_order',
                'lnk.is_primary'
            )
            ->where('lnk.subject_id', $subjectId)
            ->where('c.status', 'published')
            ->where('c.is_active', 1);

        if ($type) {
            $q->where('c.type', $type);
        }

        // تأكيد التطابق المؤسسي (في حال كان بعض الأعمدة NULL على المحتوى نسمح بها)
        $q->where('c.university_id', $user->university_id);

        if ($user->branch_id) {
            $q->where(function ($qq) use ($user) {
                $qq->whereNull('branch_id')->orWhere('branch_id', $user->branch_id);
            });
        }
        if ($user->college_id) {
            $q->where(function ($qq) use ($user) {
                $qq->whereNull('college_id')->orWhere('college_id', $user->college_id);
            });
        }
        if ($user->major_id) {
            $q->where(function ($qq) use ($user) {
                $qq->whereNull('major_id')->orWhere('major_id', $user->major_id);
            });
        }

        $rows = $q->orderByDesc('lnk.is_primary')
            ->orderBy('lnk.sort_order')
            ->orderByDesc('c.published_at')
            ->get();

        return ContentResource::collection($rows);
    }
}
