<?php
namespace Wikichua\Bliss\Concerns;

use Illuminate\Bus\Queueable as LaravelQueueable;

trait Queueable
{
    use LaravelQueueable;

    public function onQueue($queue)
    {
        if (!str()->contains($queue, ':')) {
            $batch = $queue;
            $onQueueName = $queue.':'.str()->random(10);
        } else {
            $batch = explode(':', $queue)[0];
            $onQueueName = $queue;
        }
        app(config('bliss.Models.Worker'))->query()->create([
            'batch' => $batch,
            'queue' => $onQueueName,
        ]);
        $this->queue = $onQueueName;

        return $this;
    }

    public function allOnQueue($queue)
    {
        if (!str()->contains($queue, ':')) {
            $batch = $queue;
            $onQueueName = $queue.':'.str()->random(10);
        } else {
            $batch = explode(':', $queue)[0];
            $onQueueName = $queue;
        }
        app(config('bliss.Models.Worker'))->query()->create([
            'batch' => $batch,
            'queue' => $onQueueName,
        ]);
        $this->chainQueue = $batch;
        $this->queue = $onQueueName;

        return $this;
    }
}
