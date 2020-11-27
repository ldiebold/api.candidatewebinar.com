<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        \App\Models\OnlineEventUser::observe(\App\Observers\OnlineEventUserObserver::class);
        \App\Models\User::observe(\App\Observers\UserObserver::class);
    }
}
