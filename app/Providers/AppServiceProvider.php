<?php

namespace App\Providers;

use App\Services\PhoneNumberService;
use App\Services\PhoneNumberServiceInterface;
use Illuminate\Support\ServiceProvider;
use libphonenumber\PhoneNumberUtil;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(PhoneNumberServiceInterface::class, function ($app) {
            return new PhoneNumberService(PhoneNumberUtil::getInstance());
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
