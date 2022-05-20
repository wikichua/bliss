<?php

return [
    'stubs' => [
        'path' => 'vendor/wikichua/bliss/stubs',
    ],
    'searchable' => [
        'exceptions' => [
            'Wikichua\Bliss\Models\Alert',
            'Wikichua\Bliss\Models\Audit',
            'Wikichua\Bliss\Models\FailedJob',
            'Wikichua\Bliss\Models\QueueJob',
            'Wikichua\Bliss\Models\Searchable',
            'Wikichua\Bliss\Models\Versionizer',
            'Wikichua\Bliss\Models\Worker',
            'Laravel\Sanctum\PersonalAccessToken',

            'Wikichua\Bliss\Models\Mongodb\Alert',
            'Wikichua\Bliss\Models\Mongodb\Audit',
            'Wikichua\Bliss\Models\Mongodb\Searchable',
            'Wikichua\Bliss\Models\Mongodb\Versionizer',
            'Wikichua\Bliss\Models\Mongodb\Worker',
        ],
    ],
    'snapshot' => [
        'exceptions' => [
            'Wikichua\Bliss\Models\Alert',
            'Wikichua\Bliss\Models\Audit',
            'Wikichua\Bliss\Models\FailedJob',
            'Wikichua\Bliss\Models\QueueJob',
            'Wikichua\Bliss\Models\Searchable',
            'Wikichua\Bliss\Models\Versionizer',
            'Wikichua\Bliss\Models\Worker',
            'Wikichua\Bliss\Models\Job',
            'Laravel\Sanctum\PersonalAccessToken',

            'Wikichua\Bliss\Models\Mongodb\Alert',
            'Wikichua\Bliss\Models\Mongodb\Audit',
            'Wikichua\Bliss\Models\Mongodb\Searchable',
            'Wikichua\Bliss\Models\Mongodb\Versionizer',
            'Wikichua\Bliss\Models\Mongodb\Worker',
            'Wikichua\Bliss\Models\Mongodb\Job',
        ],
    ],
    'audit' => [
        'exceptions' => [
            'Wikichua\Bliss\Models\Alert',
            'Wikichua\Bliss\Models\Audit',
            'Wikichua\Bliss\Models\FailedJob',
            'Wikichua\Bliss\Models\QueueJob',
            'Wikichua\Bliss\Models\Searchable',
            'Wikichua\Bliss\Models\Versionizer',
            'Wikichua\Bliss\Models\Worker',
            'Wikichua\Bliss\Models\Job',
            'Laravel\Sanctum\PersonalAccessToken',

            'Wikichua\Bliss\Models\Mongodb\Alert',
            'Wikichua\Bliss\Models\Mongodb\Audit',
            'Wikichua\Bliss\Models\Mongodb\Searchable',
            'Wikichua\Bliss\Models\Mongodb\Versionizer',
            'Wikichua\Bliss\Models\Mongodb\Worker',
            'Wikichua\Bliss\Models\Mongodb\Job',
        ],
        'must_include_fields' => [
            'id',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
            'deleted_by',
        ],
        'masks' => [ // masking the key in data field within the activity log model
            'password',
            'password_confirmation',
            'token',
        ],
    ],
    'Livewires' => [
        'Auth' => [
            \Wikichua\Bliss\Http\Livewire\Auth\AuthenticatedSession::class,
            \Wikichua\Bliss\Http\Livewire\Auth\ConfirmablePassword::class,
            \Wikichua\Bliss\Http\Livewire\Auth\EmailVerificationPrompt::class,
            \Wikichua\Bliss\Http\Livewire\Auth\LogoutSession::class,
            \Wikichua\Bliss\Http\Livewire\Auth\NewPassword::class,
            \Wikichua\Bliss\Http\Livewire\Auth\PasswordResetLink::class,
            \Wikichua\Bliss\Http\Livewire\Auth\Register::class,
            \Wikichua\Bliss\Http\Livewire\Auth\VerifyEmail::class,
        ],
        'Dashboard' => [
            \Wikichua\Bliss\Http\Livewire\Admin\Dashboard::class,
        ],
        'Profile' => [
            \Wikichua\Bliss\Http\Livewire\Admin\Profile::class,
        ],
        'Audit' => [
            \Wikichua\Bliss\Http\Livewire\Admin\Audit\Listing::class,
            \Wikichua\Bliss\Http\Livewire\Admin\Audit\Showing::class,
        ],
        'Log' => [
            \Wikichua\Bliss\Http\Livewire\Admin\Log\Listing::class,
            \Wikichua\Bliss\Http\Livewire\Admin\Log\Showing::class,
        ],
        'Versionizer' => [
            \Wikichua\Bliss\Http\Livewire\Admin\Versionizer\Listing::class,
            \Wikichua\Bliss\Http\Livewire\Admin\Versionizer\Showing::class,
        ],
        'QueueJob' => [
            \Wikichua\Bliss\Http\Livewire\Admin\QueueJob\Listing::class,
            \Wikichua\Bliss\Http\Livewire\Admin\QueueJob\Showing::class,
        ],
        'FailedJob' => [
            \Wikichua\Bliss\Http\Livewire\Admin\FailedJob\Listing::class,
            \Wikichua\Bliss\Http\Livewire\Admin\FailedJob\Showing::class,
        ],
        'Permission' => [
            \Wikichua\Bliss\Http\Livewire\Admin\Permission\Creating::class,
            \Wikichua\Bliss\Http\Livewire\Admin\Permission\Editing::class,
            \Wikichua\Bliss\Http\Livewire\Admin\Permission\Listing::class,
            \Wikichua\Bliss\Http\Livewire\Admin\Permission\Showing::class,
        ],
        'Role' => [
            \Wikichua\Bliss\Http\Livewire\Admin\Role\Creating::class,
            \Wikichua\Bliss\Http\Livewire\Admin\Role\Editing::class,
            \Wikichua\Bliss\Http\Livewire\Admin\Role\Listing::class,
            \Wikichua\Bliss\Http\Livewire\Admin\Role\Showing::class,
        ],
        'User' => [
            \Wikichua\Bliss\Http\Livewire\Admin\User\Creating::class,
            \Wikichua\Bliss\Http\Livewire\Admin\User\Editing::class,
            \Wikichua\Bliss\Http\Livewire\Admin\User\EditingPassword::class,
            \Wikichua\Bliss\Http\Livewire\Admin\User\Listing::class,
            \Wikichua\Bliss\Http\Livewire\Admin\User\Showing::class,
        ],
        'PersonalAccessToken' => [
            \Wikichua\Bliss\Http\Livewire\Admin\PersonalAccessToken\Creating::class,
            \Wikichua\Bliss\Http\Livewire\Admin\PersonalAccessToken\Listing::class,
            \Wikichua\Bliss\Http\Livewire\Admin\PersonalAccessToken\Showing::class,
        ],
        'Setting' => [
            \Wikichua\Bliss\Http\Livewire\Admin\Setting\Creating::class,
            \Wikichua\Bliss\Http\Livewire\Admin\Setting\Editing::class,
            \Wikichua\Bliss\Http\Livewire\Admin\Setting\Listing::class,
            \Wikichua\Bliss\Http\Livewire\Admin\Setting\Showing::class,
        ],
        'Cronjob' => [
            \Wikichua\Bliss\Http\Livewire\Admin\Cronjob\Creating::class,
            \Wikichua\Bliss\Http\Livewire\Admin\Cronjob\Editing::class,
            \Wikichua\Bliss\Http\Livewire\Admin\Cronjob\Listing::class,
            \Wikichua\Bliss\Http\Livewire\Admin\Cronjob\Showing::class,
        ],
        'Report' => [
            \Wikichua\Bliss\Http\Livewire\Admin\Report\Creating::class,
            \Wikichua\Bliss\Http\Livewire\Admin\Report\Editing::class,
            \Wikichua\Bliss\Http\Livewire\Admin\Report\Listing::class,
            \Wikichua\Bliss\Http\Livewire\Admin\Report\Showing::class,
        ],
    ],
    'Models' => array_merge([
        'Alert' => \Wikichua\Bliss\Models\Alert::class,
        'Audit' => \Wikichua\Bliss\Models\Audit::class,
        'Cronjob' => \Wikichua\Bliss\Models\Cronjob::class,
        'FailedJob' => \Wikichua\Bliss\Models\FailedJob::class,
        'Log' => \Wikichua\Bliss\Models\Log::class,
        'Permission' => \Wikichua\Bliss\Models\Permission::class,
        'PersonalAccessToken' => \Laravel\Sanctum\PersonalAccessToken::class,
        'QueueJob' => \Wikichua\Bliss\Models\QueueJob::class,
        'Report' => \Wikichua\Bliss\Models\Report::class,
        'Role' => \Wikichua\Bliss\Models\Role::class,
        'Searchable' => \Wikichua\Bliss\Models\Searchable::class,
        'Setting' => \Wikichua\Bliss\Models\Setting::class,
        'User' => config('auth.providers.users.model', \App\Models\User::class),
        'Versionizer' => \Wikichua\Bliss\Models\Versionizer::class,
        'Worker' => \Wikichua\Bliss\Models\Worker::class,
    ], [
        // in case wish to use Mongodb for specific model can uncomment all of these to overwrite the original mysql connections

        // 'Alert' => \Wikichua\Bliss\Models\Mongodb\Alert::class,
        // 'Audit' => \Wikichua\Bliss\Models\Mongodb\Audit::class,
        // 'Log' => \Wikichua\Bliss\Models\Mongodb\Log::class,
        // 'Searchable' => \Wikichua\Bliss\Models\Mongodb\Searchable::class,
        // 'Versionizer' => \Wikichua\Bliss\Models\Mongodb\Versionizer::class,
        // 'Worker' => \Wikichua\Bliss\Models\Mongodb\Worker::class,
    ]),
];
