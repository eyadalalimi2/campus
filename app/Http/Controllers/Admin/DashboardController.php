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
        // بيانات وهمية
        $uniTotal = 10;
        $uniActive = 8;
        $uniInactive = 2;
        $branchTotal = 20;
        $branchActive = 15;
        $branchInactive = 5;
        $colTotal = 30;
        $colActive = 25;
        $colInactive = 5;
        $majTotal = 40;
        $majActive = 35;
        $majInactive = 5;
        $docTotal = 50;
        $docUni = 30;
        $docInd = 20;
        $stdTotal = 1000;
        $stdActive = 800;
        $stdSuspended = 100;
        $stdGrad = 100;
        $matTotal = 60;
        $matActive = 50;
        $matInactive = 10;
        $devTotal = 15;
        $devActive = 12;
        $devInactive = 3;
        $contentTotal = 200;
        $cntFile = 120;
        $cntVideo = 60;
        $cntLink = 20;
        $activeMaterials = $matActive;
        $activeDoctors = 40;
        $activeDevices = $devActive;
        $activeContents = 180;
        $studentsPerUniversity = collect([
            (object)[ 'uname' => 'جامعة 1', 'c' => 300 ],
            (object)[ 'uname' => 'جامعة 2', 'c' => 250 ],
            (object)[ 'uname' => 'جامعة 3', 'c' => 200 ],
            (object)[ 'uname' => 'جامعة 4', 'c' => 150 ],
            (object)[ 'uname' => 'جامعة 5', 'c' => 100 ],
        ]);
        $studentsPerBranch = collect([
            (object)[ 'ub_name' => 'جامعة 1 - فرع أ', 'c' => 120 ],
            (object)[ 'ub_name' => 'جامعة 2 - فرع ب', 'c' => 110 ],
            (object)[ 'ub_name' => 'جامعة 3 - فرع ج', 'c' => 100 ],
        ]);
        $studentsMonthly = collect([
            (object)[ 'ym' => '2025-01', 'c' => 80 ],
            (object)[ 'ym' => '2025-02', 'c' => 90 ],
            (object)[ 'ym' => '2025-03', 'c' => 100 ],
            (object)[ 'ym' => '2025-04', 'c' => 110 ],
            (object)[ 'ym' => '2025-05', 'c' => 120 ],
            (object)[ 'ym' => '2025-06', 'c' => 130 ],
            (object)[ 'ym' => '2025-07', 'c' => 140 ],
            (object)[ 'ym' => '2025-08', 'c' => 150 ],
            (object)[ 'ym' => '2025-09', 'c' => 160 ],
            (object)[ 'ym' => '2025-10', 'c' => 170 ],
            (object)[ 'ym' => '2025-11', 'c' => 180 ],
            (object)[ 'ym' => '2025-12', 'c' => 190 ],
        ]);
        $latestStudents = collect([
            (object)[ 'name' => 'طالب 1', 'student_number' => '1001', 'university' => (object)['name' => 'جامعة 1'], 'created_at' => now() ],
            (object)[ 'name' => 'طالب 2', 'student_number' => '1002', 'university' => (object)['name' => 'جامعة 2'], 'created_at' => now() ],
            (object)[ 'name' => 'طالب 3', 'student_number' => '1003', 'university' => (object)['name' => 'جامعة 3'], 'created_at' => now() ],
        ]);
        $latestDoctors = collect([
            (object)[ 'name' => 'دكتور 1', 'university' => (object)['name' => 'جامعة 1'], 'created_at' => now() ],
            (object)[ 'name' => 'دكتور 2', 'university' => (object)['name' => 'جامعة 2'], 'created_at' => now() ],
        ]);
        $latestContent = collect([
            (object)[ 'title' => 'محتوى 1', 'type' => 'file', 'university' => (object)['name' => 'جامعة 1'], 'created_at' => now() ],
            (object)[ 'title' => 'محتوى 2', 'type' => 'video', 'university' => (object)['name' => 'جامعة 2'], 'created_at' => now() ],
        ]);
        $universitiesQuick = collect([
            (object)[ 'id' => 1, 'name' => 'جامعة 1' ],
            (object)[ 'id' => 2, 'name' => 'جامعة 2' ],
        ]);
        $pieStatus = [
            'active'    => $stdActive,
            'suspended' => $stdSuspended,
            'graduated' => $stdGrad,
        ];
        $pieGender = [
            'male'   => 600,
            'female' => 400,
        ];
        $inactiveUniversities = collect(['جامعة 3', 'جامعة 4']);
        $materialsWithoutContent = collect(['مادة 1', 'مادة 2']);
        $majorsWithoutDoctors = collect(['تخصص 1', 'تخصص 2']);
        $inactiveUniCount = $inactiveUniversities->count();
        $matNoContentCount = $materialsWithoutContent->count();
        $majNoDoctorsCount = $majorsWithoutDoctors->count();
        $discTotal = 5;
        $discActive = 4;
        $discInactive = 1;
        $progTotal = 6;
        $progActive = 5;
        $progInactive = 1;
        $calTotal = 3;
        $calActive = 2;
        $calInactive = 1;
        $termTotal = 4;
        $termActive = 3;
        $termInactive = 1;
        $subTotal = 20;
        $subActive = 15;
        $subOther = 5;
        $blogTotal = 10;
        $blogPublished = 6;
        $blogDraft = 3;
        $blogArchived = 1;
        $latestBlogs = collect([
            (object)[ 'title' => 'مدونة 1', 'doctor' => (object)['name' => 'دكتور 1'], 'status' => 'published', 'published_at' => now(), 'created_at' => now() ],
            (object)[ 'title' => 'مدونة 2', 'doctor' => (object)['name' => 'دكتور 2'], 'status' => 'draft', 'published_at' => null, 'created_at' => now() ],
        ]);
        return view('admin.dashboard', compact(
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
            'docTotal',
            'docUni',
            'docInd',
            'stdTotal',
            'stdActive',
            'stdSuspended',
            'stdGrad',
            'matTotal',
            'matActive',
            'matInactive',
            'devTotal',
            'devActive',
            'devInactive',
            'contentTotal',
            'cntFile',
            'cntVideo',
            'cntLink',
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
            'pieStatus',
            'pieGender',
            'inactiveUniCount',
            'matNoContentCount',
            'majNoDoctorsCount',
            'inactiveUniversities',
            'materialsWithoutContent',
            'majorsWithoutDoctors',
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
            'subTotal',
            'subActive',
            'subOther',
            'blogTotal',
            'blogPublished',
            'blogDraft',
            'blogArchived',
            'latestBlogs'
        ));
    }
}
