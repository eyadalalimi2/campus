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
        // يطبّق فقط على قوالب الموقع site.*
        View::composer('site.*', function ($view) {
            // آلية تحديد الجامعة الحالية لواجهات الطلاب:
            // 1) من الـ Session (إن وُجدت)
            // 2) أو من باراميتر ?university_id=...
            // 3) أو أول جامعة مفعّلة
            $currentUniversity = null;

            if (request()->filled('university_id')) {
                $currentUniversity = University::find(request('university_id'));
                if ($currentUniversity) {
                    session(['current_university_id' => $currentUniversity->id]);
                }
            }

            if (!$currentUniversity && session()->has('current_university_id')) {
                $currentUniversity = University::find(session('current_university_id'));
            }

            if (!$currentUniversity) {
                $currentUniversity = University::where('is_active', true)->orderBy('name')->first();
            }

            $themeVars = [
                'primary'   => $currentUniversity?->primary_color ?: '#0d6efd',
                'secondary' => $currentUniversity?->secondary_color ?: '#6c757d',
                'logoUrl'   => $currentUniversity?->logo_url ?? asset('images/logo.png'),
                'mode'      => $currentUniversity?->theme_mode ?? 'auto', // إن كنت أضفت theme_mode
            ];

            $view->with(compact('currentUniversity', 'themeVars'));
        });
    }
}
