<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\University;
use App\Models\College;
use App\Models\Major;
use App\Models\Doctor;
use App\Models\Material;
use App\Models\Device;
use App\Models\Content;
use App\Models\Asset;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // ——— المؤشرات العامة (KPIs)
        $totalStudents     = User::count();
        $totalUniversities = University::count();
        $totalColleges     = College::count();
        $totalMajors       = Major::count();
        $totalMaterials    = Material::count();
        $totalDoctors      = Doctor::count();
        $totalDevices      = Device::count();
        $totalContents     = Content::count();

        // ——— تفصيل المحتوى حسب النوع
        $contentsByType = Content::select('type', DB::raw('COUNT(*) as c'))
            ->groupBy('type')->pluck('c','type')->toArray();
        $cntFile  = $contentsByType['file']  ?? 0;
        $cntVideo = $contentsByType['video'] ?? 0;
        $cntLink  = $contentsByType['link']  ?? 0;

        // ——— حالة التفعيل
        $activeStudents  = User::where('status','active')->count();
        $activeDoctors   = Doctor::where('is_active',1)->count();
        $activeMaterials = Material::where('is_active',1)->count();
        $activeDevices   = Device::where('is_active',1)->count();
        $activeContents  = Content::where('is_active',1)->count();

        // ——— الطلاب حسب الجنس
        $genderAgg = User::select('gender', DB::raw('COUNT(*) as c'))
            ->groupBy('gender')->pluck('c','gender')->toArray();
        $maleCount   = $genderAgg['male']   ?? 0;
        $femaleCount = $genderAgg['female'] ?? 0;

        // ——— توزيع الطلاب على الجامعات (للرسم البياني)
        $studentsPerUniversity = User::select('universities.name as uname', DB::raw('COUNT(users.id) as c'))
            ->leftJoin('universities','universities.id','=','users.university_id')
            ->groupBy('universities.name')
            ->orderByDesc('c')
            ->limit(10)
            ->get();

        // ——— نمو الطلاب خلال آخر 12 شهرًا
        $start = Carbon::now()->startOfMonth()->subMonths(11);
        $studentsMonthly = User::select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as ym"),
                DB::raw('COUNT(*) as c')
            )
            ->where('created_at','>=',$start)
            ->groupBy('ym')
            ->orderBy('ym')
            ->get();

        // ——— المقارنة الشهرية (آخر 30 يومًا مقابل الـ 30 التي قبلها)
        $now = Carbon::now();
        $last30      = User::whereBetween('created_at', [$now->copy()->subDays(30), $now])->count();
        $prev30      = User::whereBetween('created_at', [$now->copy()->subDays(60), $now->copy()->subDays(30)])->count();
        $studentsDeltaPct = $prev30 > 0 ? round((($last30 - $prev30) / $prev30) * 100, 1) : null;

        // ——— أحدث السجلات
        $latestStudents = User::latest()->limit(5)->get(['id','name','student_number','university_id','created_at']);
        $latestDoctors  = Doctor::latest()->limit(5)->get(['id','name','university_id','created_at']);
        $latestContent  = Content::latest()->limit(5)->get(['id','title','type','university_id','created_at']);

        // ——— ملخص سريع للجامعات
        $universitiesQuick = University::orderBy('name')->get(['id','name']);

        return view('admin.dashboard', compact(
            'totalStudents','totalUniversities','totalColleges','totalMajors',
            'totalMaterials','totalDoctors','totalDevices','totalContents',
            'cntFile','cntVideo','cntLink',
            'activeStudents','activeDoctors','activeMaterials','activeDevices','activeContents',
            'maleCount','femaleCount',
            'studentsPerUniversity','studentsMonthly','studentsDeltaPct',
            'latestStudents','latestDoctors','latestContent',
            'universitiesQuick'
        ));
    }
}
