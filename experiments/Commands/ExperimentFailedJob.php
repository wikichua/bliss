<?php

namespace Wikichua\Bliss\Exp\Commands;

use Illuminate\Console\Command;
use Wikichua\Bliss\Exp\Jobs\ExperimentFailedJobProcess;

class ExperimentFailedJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'experiment:failed_job';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Experiment Failed Job';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // ExperimentFailedJobProcess::dispatch()->onQueue('experiment');
        dispatchToWorker(new ExperimentFailedJobProcess(), onQueue: 'experiment');
    }
}
