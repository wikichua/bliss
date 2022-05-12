<?php

namespace Wikichua\Bliss\Exp\Jobs;

use Wikichua\Bliss\Traits\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ExperimentImportKeywordsQueueProcess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public $keyword, public $max = 1000)
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $table = \DB::table('experiment_keywords_queue');
        $table->insert(['keyword' => $this->keyword, 'created_at' => now(), 'updated_at' => now()]);
        $numerics = range(0, $this->max);
        foreach ($numerics as $numeric) {
            $table->insert(['keyword' => sprintf('%s %s', $this->keyword, $numeric), 'created_at' => now(), 'updated_at' => now()]);
        }
    }
}
