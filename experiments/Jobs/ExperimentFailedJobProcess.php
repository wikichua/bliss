<?php

namespace Wikichua\Bliss\Exp\Jobs;

use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Wikichua\Bliss\Traits\Queueable;

class ExperimentFailedJobProcess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $response = \Http::withoutVerifying()->get(route('exp.random_result'));
        if ($response->status() != 200) {
            throw new \Exception("Error Processing Request", 1);
        }
    }
}
