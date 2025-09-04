<?php

namespace App\Http\Controllers\Web;

use Illuminate\View\View;

class HomeController extends \App\Http\Controllers\Controller
{
    public function index(): View
    {
        // بيانات وهمية للعرض في الصفحة الرئيسية
        $headlineStats = [
            ['label' => 'الطلاب', 'value' => 12450],
            ['label' => 'المقررات', 'value' => 320],
            ['label' => 'هيئة التدريس', 'value' => 540],
            ['label' => 'أقسام', 'value' => 18],
        ];

        $usps = [
            ['title' => 'إدارة أكاديمية شاملة', 'desc' => 'أقسام، مقررات، طلاب، وأعضاء هيئة تدريس في نظام موحّد.'],
            ['title' => 'تقارير فورية', 'desc' => 'لوحات متابعة لحظية مع رسوم بيانية لاتخاذ قرار سريع.'],
            ['title' => 'قابلية التوسع', 'desc' => 'بنية مرنة تدعم التكامل مع الأنظمة المؤسسية.'],
        ];

        return view('site.home', compact('headlineStats', 'usps'));
    }
}
