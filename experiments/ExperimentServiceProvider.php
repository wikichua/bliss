<?php

namespace Wikichua\Bliss\Exp;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Livewire\Livewire;

class ExperimentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $vendorPath = base_path('vendor/wikichua/bliss');
        if (is_dir(base_path('packages/Wikichua/Bliss'))) {
            $vendorPath = base_path('packages/Wikichua/Bliss');
        } elseif (is_dir(base_path('packages/wikichua/bliss'))) {
            $vendorPath = base_path('packages/wikichua/bliss');
        }
        if (Str::contains($vendorPath, 'packages')) {
            $this->loadViewsFrom(__DIR__.'/../experiments/views', 'bliss');
            $this->loadMigrationsFrom(__DIR__.'/../experiments/migrations');
            Livewire::component(\Wikichua\Bliss\Exp\Livewire\ExperimentFilepond::class);
            Livewire::component(\Wikichua\Bliss\Exp\Livewire\ExperimentSearching::class);
            Livewire::component(\Wikichua\Bliss\Exp\Livewire\ExperimentPoll::class);

            Route::middleware('web')->group(function ($router) {
                $router->get('exp/filepond', \Wikichua\Bliss\Exp\Livewire\ExperimentFilepond::class);
                $router->get('exp/searching', \Wikichua\Bliss\Exp\Livewire\ExperimentSearching::class);
                $router->get('exp/random_result', function () {
                    $status = array_rand([400 => 400, 200 => 200]);
                    return response(status: $status);
                })->name('exp.random_result');
                $router->get('exp/poll', \Wikichua\Bliss\Exp\Livewire\ExperimentPoll::class);
            });

            $this->commands([
                \Wikichua\Bliss\Exp\Commands\ExperimentImportKeywordsQueue::class,
                \Wikichua\Bliss\Exp\Commands\ExperimentFailedJob::class,
                \Wikichua\Bliss\Exp\Commands\ExperimentMail::class,
                \Wikichua\Bliss\Exp\Commands\ExperimentEventListener::class,
            ]);
        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

    }
}
