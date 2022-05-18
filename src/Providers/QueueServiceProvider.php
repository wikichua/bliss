<?php

namespace Wikichua\Bliss\Providers;

use App\Notifications\QueueHasLongWaitTime;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Queue\Events\JobQueued;
use Illuminate\Queue\Events\QueueBusy;
use Illuminate\Queue\QueueServiceProvider as LaravelQueueServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Wikichua\Bliss\Providers\DatabaseUuidFailedJobProvider;
use Wikichua\Bliss\Models\QueueJob;

class QueueServiceProvider extends LaravelQueueServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        parent::register();
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Event::listen(function (QueueBusy $event) {
            /*new QueueHasLongWaitTime(
                $event->connection,
                $event->queue,
                $event->size
            );*/
        // });

        Queue::before(function (JobProcessing $event) {
            $queuejob = app(config('bliss.Models.QueueJob'))->query()->updateOrCreate([
                'uuid' => $event->job->uuid(),
                'connection' => $event->connectionName,
                'queue' => $event->job->getQueue(),
                'batch' => explode(':', $event->job->getQueue())[0] ?? $event->job->getQueue(),
            ], [
                'payload' => $event->job->payload(),
                'status' => settings('queuejob_status.Processing'),
                'started_at' => now(),
            ]);
            // logger($event->job->getQueue());
            // $event->connectionName
            // $event->job
            // $event->job->payload()
        });

        Queue::after(function (JobProcessed $event) {
            app(config('bliss.Models.QueueJob'))->query()->where('uuid', $event->job->uuid())->update([
                'status' => settings('queuejob_status.Completed'),
                'ended_at' => now(),
            ]);
        });

        Queue::failing(function (JobFailed $event) {
            app(config('bliss.Models.QueueJob'))->query()->where('uuid', $event->job->uuid())->update([
                'status' => settings('queuejob_status.Error'),
                'ended_at' => now(),
            ]);
            // $event->job->fail($event->exception);
        });

        // Queue::looping(function ($job) {
        //     dd($job);
        // });
    }

    protected function databaseUuidFailedJobProvider($config)
    {
        return new DatabaseUuidFailedJobProvider(
            $this->app['db'], $config['database'], $config['table']
        );
    }
}
