<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\{
    User,
    University,
    UniversityBranch,
    College,
    Major,
    MedDoctor,
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

        // أحدث المدونات + إحصاءات المدونات
        $latestBlogs = Blog::with('doctor')
            ->orderByRaw('COALESCE(published_at, created_at) DESC')
            ->limit(5)
            ->get(['id', 'title', 'status', 'doctor_id', 'published_at', 'created_at']);

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

        // الفروع (جديد)
        $branchTotal    = UniversityBranch::count();
        $branchActive   = UniversityBranch::where('is_active', 1)->count();
        $branchInactive = $branchTotal - $branchActive;

        // الكليات
        $colTotal    = College::count();
        $colActive   = College::where('is_active', 1)->count();
        $colInactive = $colTotal - $colActive;

        // التخصصات
        $majTotal    = Major::count();
        $majActive   = Major::where('is_active', 1)->count();
        $majInactive = $majTotal - $majActive;

    // الدكاترة (من جدول med_doctors)
    $docTotal  = MedDoctor::count();
    $docActive   = MedDoctor::where('status', 'published')->count();
    $docInactive = MedDoctor::where('status', 'draft')->count();
    $docUni    = null; // لا يوجد عمود type في med_doctors
    $docInd    = null; // لا يوجد عمود type في med_doctors

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
    $activeDoctors   = $docActive;
        $activeDevices   = $devActive;
        $activeContents  = Content::where('is_active', 1)->count();

        // توزيع الطلاب: Top 10 لكل جامعة
        $studentsPerUniversity = User::select('universities.name as uname', DB::raw('COUNT(users.id) as c'))
            ->leftJoin('universities', 'universities.id', '=', 'users.university_id')
            ->groupBy('universities.name')
            ->orderByDesc('c')
            ->limit(10)->get();

        // توزيع الطلاب: Top 10 لكل فرع (جديد)
        $studentsPerBranch = User::select(
            DB::raw("CONCAT(universities.name, ' - ', university_branches.name) as ub_name"),
            DB::raw('COUNT(users.id) as c')
        )
            ->leftJoin('university_branches', 'university_branches.id', '=', 'users.branch_id')
            ->leftJoin('universities', 'universities.id', '=', 'users.university_id')
            ->groupBy('ub_name')
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
        $latestStudents = User::latest()->limit(5)->get(['id', 'name', 'student_number', 'university_id', 'branch_id', 'created_at']);
    $latestDoctors  = MedDoctor::latest()->limit(5)->get(['id', 'name', 'created_at']);
        $latestContent  = Content::latest()->limit(5)->get(['id', 'title', 'type', 'university_id', 'branch_id', 'created_at']);

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
        })
            ->whereNotExists(function ($q) {
                $q->select(DB::raw(1))
                    ->from('doctors')
                    ->whereColumn('doctors.major_id', 'majors.id');
            })->count();

        return view('admin.dashboard', compact(
            // جامعات / فروع / كليات / تخصصات
            'uniTotal',
            'uniActive',
            'uniInactive',
            'branchTotal',
            'branchActive',
            'branchInactive',
            'colTotal',
            'colActive',
            'colInactive',
            'majTotal',
            'majActive',
            'majInactive',

            // دكاترة
            'docTotal',
            'docActive',
            'docInactive',

            // طلاب
            'stdTotal',
            'stdActive',
            'stdSuspended',
            'stdGrad',

            // مواد / أجهزة
            'matTotal',
            'matActive',
            'matInactive',
            'devTotal',
            'devActive',
            'devInactive',

            // محتوى
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
            'studentsPerBranch',
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
            'termInactive',
            // متغيرات الاشتراكات
            'subTotal',
            'subActive',
            'subOther',
            // متغيرات المدونة
            'blogTotal',
            'blogPublished',
            'blogDraft',
            'blogArchived',
            'latestBlogs'
        ));
    }
}
