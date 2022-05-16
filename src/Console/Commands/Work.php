<?php

 namespace Wikichua\Bliss\Console\Commands;

use Illuminate\Console\Command;
use React\ChildProcess\Process;
use React\EventLoop\Loop;

class Work extends Command
{
    protected $signature = 'bliss:work {--backoff=1} {--worker=} {--stop-when-empty}';
    protected $description = 'Queue Worker Asynchronous';
    protected $max_workers = null;
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $backoff = !blank($this->option('backoff')) ? $this->option('backoff') : 1;
        $this->max_workers = !blank($this->option('worker')) ? $this->option('worker') : settings('max_workers');
        Loop::addPeriodicTimer($backoff, function () use($backoff) {
            $result = $this->dispatching($backoff);
            if ($this->option('stop-when-empty') && !$result) {
                Loop::stop();
            }
        });
    }

    protected function dispatching($backoff)
    {
        $workers = app(config('bliss.Models.Worker'))->query()->take($this->max_workers)->get();
        foreach ($workers as $worker) {
            $cmd = "php artisan queue:work --queue='{$worker->queue}' --tries=3 --backoff={$backoff} --stop-when-empty";
            $process = new Process($cmd);
            $process->start();
            $process->stdout->on('data', function ($data) use($worker) {
                $data = trim($data);
                if (str()->contains($data, 'Processed:')) {
                    $this->info($data);
                    $worker->delete();
                } elseif (str()->contains($data, 'Failed:')) {
                    $this->error($data);
                    $worker->delete();
                } else {
                    $this->line($data);
                }
            });

            $process->on('exit', function() use($worker, $process) {
                $queuejob = app(config('bliss.Models.QueueJob'))->query()->where('queue', $worker->queue)->whereIn('status', ['E', 'C'])->first();
                if ($queuejob) {
                    $this->info('Queue '.$worker->queue.' completed.');
                    $this->newLine();
                    $process->terminate();
                    $worker->delete();
                }
            });
        }
        return count($workers) > 0;
    }
}
