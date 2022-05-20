<?php

namespace Wikichua\Bliss\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Wikichua\Bliss\Events\AuditEvent;
use Wikichua\Bliss\Events\SearchableEvent;
use Wikichua\Bliss\Events\SnapshotEvent;
use Wikichua\Bliss\Listeners\AuditListener;
use Wikichua\Bliss\Listeners\SearchableListener;
use Wikichua\Bliss\Listeners\SnapshotListener;
use Wikichua\Bliss\Observers\AuditObserver;
use Wikichua\Bliss\Observers\SearchableObserver;
use Wikichua\Bliss\Observers\SnapshotObserver;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        AuditEvent::class => [
            AuditListener::class,
        ],
        SnapshotEvent::class => [
            SnapshotListener::class,
        ],
        SearchableEvent::class => [
            SearchableListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        $vendorPath = base_path('vendor/wikichua/bliss');
        if (is_dir(base_path('packages/Wikichua/Bliss'))) {
            $vendorPath = base_path('packages/Wikichua/Bliss');
        } elseif (is_dir(base_path('packages/wikichua/bliss'))) {
            $vendorPath = base_path('packages/wikichua/bliss');
        }
        if (Str::contains($vendorPath, 'packages')) {
            app(\Wikichua\Bliss\Exp\Models\ExperimentKeywordsModel::class)->observe(AuditObserver::class);
            app(\Wikichua\Bliss\Exp\Models\ExperimentKeywordsModel::class)->observe(SnapshotObserver::class);
            app(\Wikichua\Bliss\Exp\Models\ExperimentKeywordsModel::class)->observe(SearchableObserver::class);
        }

        $modelsList = getModelsList();
        foreach ($modelsList as $modelClass) {
            // exclude experiments
            if (Str::contains($modelClass, 'Wikichua\Bliss\Exp\Models')) {
                continue;
            }
            if (!in_array($modelClass, config('bliss.audit.exceptions'))) {
                app($modelClass)->observe(AuditObserver::class);
            }
            if (!in_array($modelClass, config('bliss.snapshot.exceptions'))) {
                app($modelClass)->observe(SnapshotObserver::class);
            }
            if (!in_array($modelClass, config('bliss.searchable.exceptions'))) {
                app($modelClass)->observe(SearchableObserver::class);
            }
        }
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
