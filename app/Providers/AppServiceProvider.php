<?php

namespace App\Providers;

use App\Services\PostServices;
use App\Services\SendEmailService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        \DB::listen(function ($sql, $bindings, $time) {
            \Log::info(sprintf("\nsql: %s\nbinds: %s\ntime: %s", $sql, var_export($bindings, true), $time));
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->singleton('PostServices', function () {
            return new PostServices();
        });
        //
        $this->app->singleton('SendEmailService', function () {
            return new SendEmailService();
        });
    }
}
