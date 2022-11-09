<?php

namespace Wikichua\Bliss\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Wikichua\Bliss\Concerns\Queueable;

class ReportProcess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(protected Model $report)
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $report = $this->report;

        cache()->remember(
            'report-'.str_slug($report->name),
            $report->cache_ttl,
            function () use ($report) {
                $results = [];
                if (count($report->queries)) {
                    foreach ($report->queries as $sql) {
                        $results[] = array_map(function ($value) {
                            return (array) $value;
                        }, \DB::select($sql));
                    }
                }

                return $results;
            }
        );
        $report->generated_at = \Carbon\Carbon::now();
        $report->next_generate_at = \Carbon\Carbon::now()->addSeconds($report->cache_ttl);
        $report->saveQuietly();

        sendAlertNotificationNow(
            message: __('Report (:name) processes completed.', [
                'name' => 'report-'.str_slug($report->name),
            ]),
            sender: config('bliss.admin.id'),
            receivers: userIdsWithPermission('read-failedjobs'),
            link: $report->readUrl,
        );
    }
}
