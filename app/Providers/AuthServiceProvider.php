<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate; // ✅ فعّل Gate
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

use App\Models\Complaint;
use App\Policies\ComplaintPolicy;
use App\Models\StudentRequest;
use App\Policies\StudentRequestPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Complaint::class      => ComplaintPolicy::class,
        StudentRequest::class => StudentRequestPolicy::class,
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        /**
         * Gates عامة للوحة التحكم وإدارة الأصول العامة
         * - access-admin  : صلاحية الدخول للوحة التحكم
         * - manage-assets : صلاحية إدارة الأصول العامة (إنشاء/تعديل/حذف/أرشفة)
         * عدّل المنطق أدناه حسب نظام الأدوار لديك.
         */
        Gate::define('access-admin', function ($user) {
            return (bool)($user->is_admin ?? false);
        });

        Gate::define('manage-assets', function ($user) {
            return (bool)($user->is_admin ?? false);
        });

        // (اختياري) إن أردت صلاحيات لإدارة التصنيف العام:
        // Gate::define('manage-public-taxonomy', fn($user) => (bool)($user->is_admin ?? false));
    }
}
