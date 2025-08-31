<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // بطاقات KPI وهمية
        $kpis = [
            ['title' => 'إجمالي الطلاب',      'value' => 12450,    'delta' => '+4.2%',  'icon' => 'users'],
            ['title' => 'المقررات النشطة',     'value' => 320,      'delta' => '+1.3%',  'icon' => 'book-open'],
            ['title' => 'هيئة التدريس',        'value' => 540,      'delta' => '-0.8%',  'icon' => 'briefcase'],
            ['title' => 'الإيرادات الشهرية',    'value' => 48720,    'delta' => '+12.6%', 'icon' => 'credit-card'],
        ];

        // سلاسل زمنية (12 شهرًا)
        $months = ['ينا','فبر','مار','أبر','ماي','يون','يول','أغس','سبت','أكت','نوف','ديس'];

        $revenueSeries = [
            'labels' => $months,
            'data'   => [21000, 22500, 24800, 26000, 27800, 30000, 32200, 34500, 36800, 41000, 45500, 48720],
        ];

        $usersSeries = [
            'labels' => $months,
            'data'   => [820, 860, 910, 950, 990, 1030, 1080, 1120, 1180, 1250, 1310, 1390],
        ];

        // توزيع الأقسام
        $deptDistribution = [
            'labels' => ['علوم الحاسب', 'هندسة', 'إدارة', 'آداب', 'علوم'],
            'data'   => [35, 25, 15, 12, 13],
        ];

        // أحدث عمليات تسجيل (Enrollments) وهمية
        $recentEnrollments = [
            ['student' => 'سارة أحمد',     'course' => 'CS101 - مقدمة برمجة', 'status' => 'enrolled',  'date' => '2025-08-29'],
            ['student' => 'محمد علي',      'course' => 'MG210 - مبادئ الإدارة','status' => 'completed', 'date' => '2025-08-28'],
            ['student' => 'أحمد سعيد',     'course' => 'EN150 - كتابة أكاديمية','status' => 'dropped',  'date' => '2025-08-27'],
            ['student' => 'ليان خالد',     'course' => 'EE201 - دوائر كهربائية','status' => 'enrolled', 'date' => '2025-08-26'],
            ['student' => 'رنا منصور',     'course' => 'CS240 - هياكل بيانات', 'status' => 'enrolled',  'date' => '2025-08-25'],
        ];

        // تنبيهات/إشعارات وهمية
        $notifications = [
            ['type' => 'warning', 'text' => 'نسبة الانسحاب زادت 3% هذا الأسبوع.'],
            ['type' => 'info',    'text' => 'تذكير: تسجيل الفصل القادم يبدأ 10 سبتمبر.'],
            ['type' => 'success', 'text' => 'اكتملت معالجة نتائج الفصل السابق.'],
        ];

        // مهام سريعة
        $tasks = [
            ['title' => 'مراجعة مقررات قسم علوم الحاسب', 'done' => false],
            ['title' => 'اعتماد خطة التدريب لأعضاء هيئة التدريس', 'done' => true],
            ['title' => 'تحديث سياسة الحد الأدنى للساعات', 'done' => false],
        ];

        return view('admin.dashboard', compact(
            'kpis',
            'revenueSeries',
            'usersSeries',
            'deptDistribution',
            'recentEnrollments',
            'notifications',
            'tasks'
        ));
    }
}
