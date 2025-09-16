<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
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
        
        Complaint::class => ComplaintPolicy::class,
        StudentRequest::class => StudentRequestPolicy::class,

        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        //
    }
}
