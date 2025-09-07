<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\{
    User,
    University,
    College,
    Major,
    Doctor,
    Material,
    Device,
    Content,
    Asset,
    Blog,
    Subscription,
    Discipline,
    Program,
    AcademicCalendar,
    AcademicTerm
};

class DashboardController extends Controller
{
    public function index()
    {
        // المجالات
        $discTotal    = Discipline::count();
        $discActive   = Discipline::where('is_active', 1)->count();
        $discInactive = $discTotal - $discActive;

        // البرامج
        $progTotal    = Program::count();
        $progActive   = Program::where('is_active', 1)->count();
        $progInactive = $progTotal - $progActive;

        // التقاويم الأكاديمية
        $calTotal     = AcademicCalendar::count();
        $calActive    = AcademicCalendar::where('is_active', 1)->count();
        $calInactive  = $calTotal - $calActive;

        // الفصول الأكاديمية
        $termTotal    = AcademicTerm::count();
        $termActive   = AcademicTerm::where('is_active', 1)->count();
        $termInactive = $termTotal - $termActive;

        // أحدث المدونات
        $latestBlogs = Blog::with('doctor')
            ->orderByRaw('COALESCE(published_at, created_at) DESC')
            ->limit(5)
            ->get(['id', 'title', 'status', 'doctor_id', 'published_at', 'created_at']);

        // إحصاءات المدونات
        $blogTotal     = Blog::count();
        $blogPublished = Blog::where('status', 'published')->count();
        $blogDraft     = Blog::where('status', 'draft')->count();
        $blogArchived  = Blog::where('status', 'archived')->count();

        // الاشتراكات
        $subTotal  = Subscription::count();
        $subActive = Subscription::where('status', 'active')->count();
        $subOther  = $subTotal - $subActive; // expired + canceled

        // الجامعات
        $uniTotal    = University::count();
        $uniActive   = University::where('is_active', 1)->count();
        $uniInactive = $uniTotal - $uniActive;

        // الكليات
        $colTotal    = College::count();
        $colActive   = College::where('is_active', 1)->count();
        $colInactive = $colTotal - $colActive;

        // التخصصات
        $majTotal    = Major::count();
        $majActive   = Major::where('is_active', 1)->count();
        $majInactive = $majTotal - $majActive;

        // الدكاترة
        $docTotal  = Doctor::count();
        $docUni    = Doctor::where('type', 'university')->count();
        $docInd    = Doctor::where('type', 'independent')->count();

        // الطلاب
        $stdTotal     = User::count();
        $stdActive    = User::where('status', 'active')->count();
        $stdSuspended = User::where('status', 'suspended')->count();
        $stdGrad      = User::where('status', 'graduated')->count();

        // المواد
        $matTotal    = Material::count();
        $matActive   = Material::where('is_active', 1)->count();
        $matInactive = $matTotal - $matActive;

        // الأجهزة
        $devTotal    = Device::count();
        $devActive   = Device::where('is_active', 1)->count();
        $devInactive = $devTotal - $devActive;

        // المحتوى حسب النوع
        $contentTotal   = Content::count();
        $contentsByType = Content::select('type', DB::raw('COUNT(*) as c'))
            ->groupBy('type')->pluck('c', 'type')->toArray();
        $cntFile  = $contentsByType['file']  ?? 0;
        $cntVideo = $contentsByType['video'] ?? 0;
        $cntLink  = $contentsByType['link']  ?? 0;

        // تفعيل العناصر الأخرى
        $activeMaterials = $matActive;
        $activeDoctors   = Doctor::where('is_active', 1)->count();
        $activeDevices   = $devActive;
        $activeContents  = Content::where('is_active', 1)->count();

        // توزيع الطلاب على الجامعات (Top 10)
        $studentsPerUniversity = User::select('universities.name as uname', DB::raw('COUNT(users.id) as c'))
            ->leftJoin('universities', 'universities.id', '=', 'users.university_id')
            ->groupBy('universities.name')
            ->orderByDesc('c')
            ->limit(10)->get();

        // نمو الطلاب خلال آخر 12 شهرًا
        $start = Carbon::now()->startOfMonth()->subMonths(11);
        $studentsMonthly = User::select(
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as ym"),
            DB::raw('COUNT(*) as c')
        )
            ->where('created_at', '>=', $start)
            ->groupBy('ym')
            ->orderBy('ym')
            ->get();

        // أحدث السجلات
        $latestStudents = User::latest()->limit(5)->get(['id', 'name', 'student_number', 'university_id', 'created_at']);
        $latestDoctors  = Doctor::latest()->limit(5)->get(['id', 'name', 'university_id', 'created_at']);
        $latestContent  = Content::latest()->limit(5)->get(['id', 'title', 'type', 'university_id', 'created_at']);

        // ملخص سريع للجامعات
        $universitiesQuick = University::orderBy('name')->get(['id', 'name']);

        // بيانات المخططات الدائرية (Pie)
        $pieStatus = [
            'active'    => $stdActive,
            'suspended' => $stdSuspended,
            'graduated' => $stdGrad,
        ];
        $genderAgg = User::select('gender', DB::raw('COUNT(*) as c'))
            ->groupBy('gender')->pluck('c', 'gender')->toArray();
        $pieGender = [
            'male'   => $genderAgg['male']   ?? 0,
            'female' => $genderAgg['female'] ?? 0,
        ];

        // تنبيهات
        $inactiveUniversities = University::where('is_active', 0)
            ->orderBy('name')->limit(5)->pluck('name');

        $materialsWithoutContent = Material::whereNotExists(function ($q) {
            $q->select(DB::raw(1))
                ->from('contents')
                ->whereColumn('contents.material_id', 'materials.id');
        })
            ->orderBy('name')->limit(5)->pluck('name');

        $majorsWithoutDoctors = Major::whereNotExists(function ($q) {
            $q->select(DB::raw(1))
                ->from('doctor_major')
                ->whereColumn('doctor_major.major_id', 'majors.id');
        })
            ->whereNotExists(function ($q) {
                $q->select(DB::raw(1))
                    ->from('doctors')
                    ->whereColumn('doctors.major_id', 'majors.id');
            })
            ->orderBy('name')->limit(5)->pluck('name');

        $inactiveUniCount  = $inactiveUniversities->count();
        $matNoContentCount = Material::whereNotExists(function ($q) {
            $q->select(DB::raw(1))
                ->from('contents')
                ->whereColumn('contents.material_id', 'materials.id');
        })->count();
        $majNoDoctorsCount = Major::whereNotExists(function ($q) {
            $q->select(DB::raw(1))
                ->from('doctor_major')
                ->whereColumn('doctor_major.major_id', 'majors.id');
        })->whereNotExists(function ($q) {
            $q->select(DB::raw(1))
                ->from('doctors')
                ->whereColumn('doctors.major_id', 'majors.id');
        })->count();

        return view('admin.dashboard', compact(
            // جامعات / كليات / تخصصات
            'uniTotal',
            'uniActive',
            'uniInactive',
            'colTotal',
            'colActive',
            'colInactive',
            'majTotal',
            'majActive',
            'majInactive',

            // دكاترة
            'docTotal',
            'docUni',
            'docInd',

            // الطلاب
            'stdTotal',
            'stdActive',
            'stdSuspended',
            'stdGrad',

            // المواد / الأجهزة
            'matTotal',
            'matActive',
            'matInactive',
            'devTotal',
            'devActive',
            'devInactive',

            // المحتوى
            'contentTotal',
            'cntFile',
            'cntVideo',
            'cntLink',

            // إضافات
            'activeMaterials',
            'activeDoctors',
            'activeDevices',
            'activeContents',
            'studentsPerUniversity',
            'studentsMonthly',
            'latestStudents',
            'latestDoctors',
            'latestContent',
            'universitiesQuick',

            // Pie data
            'pieStatus',
            'pieGender',

            // تنبيهات
            'inactiveUniCount',
            'matNoContentCount',
            'majNoDoctorsCount',
            'inactiveUniversities',
            'materialsWithoutContent',
            'majorsWithoutDoctors',

            // المدونات والاشتراكات
            'blogTotal',
            'blogPublished',
            'blogDraft',
            'blogArchived',
            'subTotal',
            'subActive',
            'subOther',
            'latestBlogs',

            // المجالات / البرامج / التقاويم / الفصول
            'discTotal',
            'discActive',
            'discInactive',
            'progTotal',
            'progActive',
            'progInactive',
            'calTotal',
            'calActive',
            'calInactive',
            'termTotal',
            'termActive',
            'termInactive'
        ));
    }
}
