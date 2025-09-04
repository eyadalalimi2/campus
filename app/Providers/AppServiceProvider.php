<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\University;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        // لا تشغّل composer أثناء أوامر Artisan
        if (app()->runningInConsole()) return;
        // يطبّق فقط على قوالب الموقع site.*
        View::composer('site.*', function ($view) {
            $currentUniversity = null;

            // 1) التقاط الاختيار من الـ GET
            if (request()->filled('university_id')) {
                if (request('university_id') === 'default') {
                    // رجوع للثيم الافتراضي
                    session()->forget('current_university_id');
                    $currentUniversity = null;
                } else {
                    // تخزين الجامعة المختارة
                    $u = University::find(request('university_id'));
                    if ($u) {
                        session(['current_university_id' => $u->id]);
                        $currentUniversity = $u;
                    }
                }
            }

            // 2) من الجلسة إذا لم يُمرر شيء الآن
            if (!$currentUniversity && session()->has('current_university_id')) {
                $currentUniversity = University::find(session('current_university_id'));
            }

            // 3) افتراض: لا جامعة محددة = ثيم افتراضي
            // الثيم الافتراضي (مسارات ثابتة بدون asset() هنا)
            $defaults = [
                'primary'    => '#0d6efd',
                'secondary'  => '#6c757d',
                'logoPath'   => '/storage/images/icon.png',
                'faviconPath' => '/storage/images/default-favicon.ico',
                'mode'       => 'auto',
            ];

            // بناء themeVars
            if ($currentUniversity) {
                $themeVars = [
                    'primary'    => $currentUniversity->primary_color   ?: $defaults['primary'],
                    'secondary'  => $currentUniversity->secondary_color ?: $defaults['secondary'],
                    // إن كانت لديك accessors logo_url / favicon_url استخدمها، وإلا عدّل حسب حقولك
                    'logoPath'   => $currentUniversity->logo_url        ?: $defaults['logoPath'],
                    'faviconPath' => $currentUniversity->favicon_url     ?: $defaults['faviconPath'],
                    'mode'       => $currentUniversity->theme_mode      ?? $defaults['mode'],
                ];
            } else {
                // ثيم افتراضي
                $themeVars = $defaults;
            }

            $view->with(compact('currentUniversity', 'themeVars'));
        });
    }
}
