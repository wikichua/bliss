<?php

namespace Wikichua\Bliss\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadWebRoutes()->loadScopeBindings();
    }

    protected function loadWebRoutes()
    {
        Route::middleware('web')->group(function ($router) {
            $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
            $this->loadRoutesFrom(__DIR__.'/../../routes/auth.php');
            $router->impersonate();
        });

        return $this;
    }

    protected function loadScopeBindings()
    {
        Route::bind('user', function ($value) {
            return app(config('bliss.Models.User'))->query()->findOrFail($value);
        });

        return $this;
    }
}
