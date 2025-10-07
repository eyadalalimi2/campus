<?php
namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Setting;

class ViewServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer('admin.*', function ($view) {
            $view->with('setting', Setting::first());
        });
    }
}
