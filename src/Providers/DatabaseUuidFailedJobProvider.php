<?php

namespace Wikichua\Bliss\Providers;

use Illuminate\Queue\Failed\DatabaseUuidFailedJobProvider as LaravelDatabaseUuidFailedJobProvider;

class DatabaseUuidFailedJobProvider extends LaravelDatabaseUuidFailedJobProvider
{
    public function log($connection, $queue, $payload, $exception)
    {
        $model = app(config('bliss.Models.FailedJob'))->query()->create([
            'uuid' => $uuid = json_decode($payload, true)['uuid'],
            'connection' => $connection,
            'batch' => explode(':', $queue)[0] ?? $queue,
            'queue' => $queue,
            'payload' => $payload,
            'exception' => (string) mb_convert_encoding($exception, 'UTF-8'),
            'failed_at' => now(),
        ]);

        sendAlertNotificationNow(
            message: __('FailedJob (:queue) added.', [
                'queue' => $queue,
            ]),
            sender: config('bliss.admin.id'),
            receivers: userIdsWithPermission('read-failedjobs'),
            link: $model->readUrl,
        );

        return $uuid;
    }
}
