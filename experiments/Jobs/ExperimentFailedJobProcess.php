<?php

namespace Wikichua\Bliss\Exp\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Wikichua\Bliss\Concerns\Queueable;

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
        Http::fake([
            '*' => fn () => Http::response('', array_rand([400 => 400, 200 => 200])),
        ]);
        if (Http::withoutVerifying()->get('')->status() != 200) {
            throw new \Exception('Error Processing Request', 1);
        }
    }
}
