<?php

namespace Wikichua\Bliss\Exp\Commands;

use Illuminate\Console\Command;
use Wikichua\Bliss\Exp\Mail\ExperimentMail as MailExperimentMail;

class ExperimentMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'experiment:mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Experiment Mail';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $message = (new MailExperimentMail('Tester'))
                ->onQueue('emails');

        \Mail::to('admin@email.com')
            ->queue($message);
    }
}
