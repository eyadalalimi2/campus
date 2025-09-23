<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class MedicalServiceProvider extends ServiceProvider {
    public function register(): void {}
    public function boot(): void {
        $this->loadRoutesFrom(base_path('routes/medical_admin.php'));
        $this->loadViewsFrom(resource_path('views/medical'), 'medical');
    }
}
