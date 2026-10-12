<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\ActivityLog;
use App\Observers\ActivityObserver;

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
        foreach (array_keys(ActivityLog::SUBJECTS) as $modelClass) {
        if (class_exists($modelClass)) {
            $modelClass::observe(ActivityObserver::class);
        }
    }
}

}
