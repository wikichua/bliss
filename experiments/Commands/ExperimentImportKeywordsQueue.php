<?php

namespace Wikichua\Bliss\Exp\Commands;

use Illuminate\Console\Command;
use Wikichua\Bliss\Exp\Jobs\ExperimentImportKeywordsQueueProcess;

class ExperimentImportKeywordsQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'experiment:import_keywords_queue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Experiment Import Keywords Queue';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        \DB::table('experiment_keywords_queue')->truncate();

        $max = 1000;
        $keyword = 'facebook';
        ExperimentImportKeywordsQueueProcess::dispatch($keyword, $max)->onQueue('experiment');

        $max = 500;
        $keyword = 'twitter';
        dispatch(function () use ($keyword, $max) {
            $table = \DB::table('experiment_keywords_queue');
            $table->insert(['keyword' => $keyword, 'created_at' => now(), 'updated_at' => now()]);
            $numerics = range(0, $max);
            foreach ($numerics as $numeric) {
                $table->insert(['keyword' => sprintf('%s %s', $keyword, $numeric), 'created_at' => now(), 'updated_at' => now()]);
            }
        })->onQueue('experiment');

        dispatchToWorker([
            new ExperimentImportKeywordsQueueProcess('google', 500),
            function () {
                $keyword = 'instagram';
                $table = \DB::table('experiment_keywords_queue');
                $table->insert(['keyword' => $keyword, 'created_at' => now(), 'updated_at' => now()]);
                $numerics = range(0, 250);
                foreach ($numerics as $numeric) {
                    $table->insert(['keyword' => sprintf('%s %s', $keyword, $numeric), 'created_at' => now(), 'updated_at' => now()]);
                }
            },
        ], onQueue: 'experiment');

        dispatch(new ExperimentImportKeywordsQueueProcess('bing', 1000))->onQueue('experiment');
        ExperimentImportKeywordsQueueProcess::dispatch('yahoo', 1000)->onQueue('experiment');
    }
}
