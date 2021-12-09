<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Models\Role;

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
        $this->registerPolicies();

        // Blade directives
        Blade::if('isAdmin', fn() => (auth()->user()->role->access_level >= Role::LEVEL_ADMIN));
        Blade::if('hasProfile', fn() => (!is_null(auth()->user()->profile)));
    }
}
