<?php

namespace Wikichua\Bliss\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Wikichua\Bliss\Jobs\ReportProcess;

class Report extends Command
{
    protected $signature = 'bliss:report {name?} {--queue} {--clear}';

    protected $description = 'Generating Report';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $name = $this->argument('name', '');
        $queue = $this->option('queue');
        $clear = $this->option('clear');
        $reports = app(config('bliss.Models.Report'))->query()->where('status', 'A');
        if ('' != $name) {
            $reports->where('name', $name);
        }
        $reports = $reports->get();
        foreach ($reports as $report) {
            if ($clear) {
                cache()->forget('report-'.str_slug($report->name));
            }
            if (null == Cache::get('report-'.str_slug($report->name))) {
                if ($queue) {
                    $this->queue($report);
                } else {
                    $this->sync($report);
                }
            }
        }
    }

    protected function queue($report)
    {
        // https://laravel.com/docs/9.x/queues#supervisor-configuration
        // art bliss:work
        ReportProcess::dispatch($report)->onQueue('report');
    }

    protected function sync($report)
    {
        cache()->remember(
            'report-'.str_slug($report->name),
            $report->cache_ttl,
            function () use ($report) {
                $results = [];
                if (count($report->queries)) {
                    $bar = $this->output->createProgressBar(count($report->queries));
                    $bar->start();
                    foreach ($report->queries as $sql) {
                        $results[] = array_map(function ($value) {
                            return (array) $value;
                        }, \DB::select($sql));
                        $bar->advance();
                    }
                    $bar->finish();
                    $this->newLine();
                }

                $report->generated_at = \Carbon\Carbon::now();
                $report->next_generate_at = \Carbon\Carbon::now()->addSeconds($report->cache_ttl);
                $report->saveQuietly();

                sendAlertNotificationNow(
                    message: __('Report (:name) processes completed.', [
                        'name' => 'report-'.str_slug($report->name),
                    ]),
                    sender: 1,
                    receivers: userIdsWithPermission('read-failedjobs'),
                    link: $report->readUrl,
                );

                return $results;
            }
        );
    }
}
