<?php

namespace Wikichua\Bliss\Providers;

use Wikichua\Bliss\Observers\SearchableObserver;
use Wikichua\Bliss\Events\SearchableEvent;
use Wikichua\Bliss\Listeners\SearchableListener;
use Wikichua\Bliss\Observers\SnapshotObserver;
use Wikichua\Bliss\Events\SnapshotEvent;
use Wikichua\Bliss\Listeners\SnapshotListener;
use Wikichua\Bliss\Observers\AuditObserver;
use Wikichua\Bliss\Events\AuditEvent;
use Wikichua\Bliss\Listeners\AuditListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

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
        app(\Wikichua\Bliss\Exp\Models\ExperimentKeywordsModel::class)->observe(AuditObserver::class);
        app(\Wikichua\Bliss\Exp\Models\ExperimentKeywordsModel::class)->observe(SnapshotObserver::class);
        app(\Wikichua\Bliss\Exp\Models\ExperimentKeywordsModel::class)->observe(SearchableObserver::class);

        $modelClassRegisteredUnderBlissConfig = config('bliss.Models');
        foreach ($modelClassRegisteredUnderBlissConfig as $modelClass) {
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
