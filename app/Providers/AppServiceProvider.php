<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //

        // Register reCAPTCHA v3 validator
        Validator::extend('recaptcha', 'App\\Validators\\ReCaptcha@validate', __('Captcha verification failed'));

        Paginator::useBootstrap();
    }
}
