<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // if env is production register app public path inside folder blog11 which in inside public_html folder
        
        if (env('APP_ENV') === 'production') {
            $this->app->bind('path.public', function () {
                return base_path('public_html/api');
            });
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::preventLazyLoading();

    }
}
