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
        // TODO: remove directive and replace it with policy accordingly
        Blade::if('hasSystemAccess', fn() => (auth()->user()->hasSystemAccess()));
        Blade::if('hasProfile', fn() => (!is_null(auth()->user()->profile)));
    }
}
