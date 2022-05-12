<?php

namespace Wikichua\Bliss;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Livewire\Livewire;

class BlissServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        try {
            // if db is not setup. don continue
            $table = app(config('bliss.Models.User'))->query()->getModel()->getTable();
            $hasTable = Schema::hasTable($table);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($this->app->runningInConsole()) {
                echo "\e[1;37;41m Don't forget to setup the \"".env('DB_DATABASE')."\" database ya. \e[0m\n";
                exit();
            }
        }

        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'wikichua');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'bliss');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadLivewireComponents();
        $this->loadGatePermissions();
        $this->loadConfigSettings();
        $this->loadMiddlewares();
        $this->loadMacros();

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
        $this->commands([
            Console\Commands\Report::class,
            Console\Commands\Work::class,
        ]);
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/bliss.php', 'bliss');

        // Register the service the package provides.
        $this->app->singleton('bliss', function ($app) {
            return new Bliss;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['bliss'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/bliss.php' => config_path('bliss.php'),
        ], 'bliss.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/wikichua'),
        ], 'bliss.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/wikichua'),
        ], 'bliss.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/wikichua'),
        ], 'bliss.views');*/

        // Registering package commands.
        $this->commands([
            Console\Commands\Installation::class,
            Console\Commands\Searchable::class,
        ]);
    }

    protected function loadLivewireComponents()
    {
        $components = collect(config('bliss.Livewires'))->flatten();
        foreach ($components as $component) {
            $baseName = strtolower(basename(str_replace(['Wikichua\\Bliss\\Http\\Livewire\\','\\'], ['','-'], $component)));
            Livewire::component($baseName, $component);
        }
        Livewire::component('reauth', \Wikichua\Bliss\Http\Livewire\Components\ReAuth::class);
        Livewire::component('searchable', \Wikichua\Bliss\Http\Livewire\Components\Searchable::class);
        Livewire::component('header-profile', \Wikichua\Bliss\Http\Livewire\Components\HeaderProfile::class);
        Livewire::component('alert-notification', \Wikichua\Bliss\Http\Livewire\Components\AlertNotification::class);
    }

    protected function loadGatePermissions()
    {
        try {
            Gate::before(function ($user, $permission) {
                if ($user->hasPermission($permission)) {
                    return true;
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            if ($this->app->runningInConsole()) {
                echo "\e[1;37;41m Don't forget to setup the ".env('DB_DATABASE')." database ya. \e[0m\n";
            }
        }
    }

    protected function loadConfigSettings()
    {
        $table = app(config('bliss.Models.Setting'))->query()->getModel()->getTable();
        $hasTable = Schema::hasTable($table);
        if ($hasTable) {
            $settings = cache()->remember('config-settings', 60 * 60, function () {
                return app(config('bliss.Models.Setting'))->all();
            });
            foreach ($settings as $setting) {
                Config::set('settings.'.$setting->key, $setting->value);
            }
        }
    }

    protected function loadMiddlewares()
    {
    }

    protected function loadMacros()
    {
        str()->macro('bytesToHuman', function ($value) {
            $units = ['Bytes', 'KB', 'MB', 'GB', 'TB'];

            for ($i = 0; $value > 1024; $i++) {
                $value /= 1024;
            }

            return number_format($value, 2) . ' ' . $units[$i];
        });
    }
}
