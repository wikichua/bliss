<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::middleware(['auth', 'can:access-admin-panel'])->group(function () {
    Route::get('/dashboard', \Wikichua\Bliss\Http\Livewire\Admin\Dashboard::class)->name('dashboard');

    Route::get('/profile', \Wikichua\Bliss\Http\Livewire\Admin\Profile::class)->name('profile');

    Route::middleware(['can:read-audits'])->prefix('audit')->name('audit.')->group(function () {
        Route::get('/', \Wikichua\Bliss\Http\Livewire\Admin\Audit\Listing::class)->name('list');
        Route::get('/{id}/show', \Wikichua\Bliss\Http\Livewire\Admin\Audit\Showing::class)->name('show');
    });

    Route::middleware(['can:read-users'])->prefix('user')->name('user.')->group(function () {
        Route::get('/', \Wikichua\Bliss\Http\Livewire\Admin\User\Listing::class)->name('list');
        Route::get('/{id}/show', \Wikichua\Bliss\Http\Livewire\Admin\User\Showing::class)->name('show');
        Route::get('/create', \Wikichua\Bliss\Http\Livewire\Admin\User\Creating::class)->name('create')
            ->middleware(['can:create-users']);
        Route::get('/{id}/edit', \Wikichua\Bliss\Http\Livewire\Admin\User\Editing::class)->name('edit')
            ->middleware(['can:update-users']);
        Route::get('/{id}/edit/password', \Wikichua\Bliss\Http\Livewire\Admin\User\EditingPassword::class)->name('edit.password')
            ->middleware(['can:update-users-password']);
    });

    Route::middleware(['can:read-personal-access-token'])->prefix('user/personal-access-token/{user}')->name('user.personal-access-token.')->group(function () {
        Route::get('/', \Wikichua\Bliss\Http\Livewire\Admin\PersonalAccessToken\Listing::class)->name('list');
        Route::get('/create', \Wikichua\Bliss\Http\Livewire\Admin\PersonalAccessToken\Creating::class)->name('create')
            ->middleware(['can:create-users']);
        Route::get('/{id}/show', \Wikichua\Bliss\Http\Livewire\Admin\PersonalAccessToken\Showing::class)->name('show');
    });

    Route::middleware(['can:read-permissions'])->prefix('permission')->name('permission.')->group(function () {
        Route::get('/', \Wikichua\Bliss\Http\Livewire\Admin\Permission\Listing::class)->name('list');
        Route::get('/{id}/show', \Wikichua\Bliss\Http\Livewire\Admin\Permission\Showing::class)->name('show');
        Route::get('/create', \Wikichua\Bliss\Http\Livewire\Admin\Permission\Creating::class)->name('create')
            ->middleware(['can:create-permissions']);
        Route::get('/{id}/edit', \Wikichua\Bliss\Http\Livewire\Admin\Permission\Editing::class)->name('edit')
            ->middleware(['can:update-permissions']);
    });

    Route::middleware(['can:read-roles'])->prefix('role')->name('role.')->group(function () {
        Route::get('/', \Wikichua\Bliss\Http\Livewire\Admin\Role\Listing::class)->name('list');
        Route::get('/{id}/show', \Wikichua\Bliss\Http\Livewire\Admin\Role\Showing::class)->name('show');
        Route::get('/create', \Wikichua\Bliss\Http\Livewire\Admin\Role\Creating::class)->name('create')
            ->middleware(['can:create-roles']);
        Route::get('/{id}/edit', \Wikichua\Bliss\Http\Livewire\Admin\Role\Editing::class)->name('edit')
            ->middleware(['can:update-roles']);
    });

    Route::middleware(['can:read-settings'])->prefix('setting')->name('setting.')->group(function () {
        Route::get('/', \Wikichua\Bliss\Http\Livewire\Admin\Setting\Listing::class)->name('list');
        Route::get('/{id}/show', \Wikichua\Bliss\Http\Livewire\Admin\Setting\Showing::class)->name('show');
        Route::get('/create', \Wikichua\Bliss\Http\Livewire\Admin\Setting\Creating::class)->name('create')
            ->middleware(['can:create-settings']);
        Route::get('/{id}/edit', \Wikichua\Bliss\Http\Livewire\Admin\Setting\Editing::class)->name('edit')
            ->middleware(['can:update-settings']);
    });

    Route::middleware(['can:read-cronjobs'])->prefix('cronjob')->name('cronjob.')->group(function () {
        Route::get('/', \Wikichua\Bliss\Http\Livewire\Admin\Cronjob\Listing::class)->name('list');
        Route::get('/{id}/show', \Wikichua\Bliss\Http\Livewire\Admin\Cronjob\Showing::class)->name('show');
        Route::get('/create', \Wikichua\Bliss\Http\Livewire\Admin\Cronjob\Creating::class)->name('create')
            ->middleware(['can:create-cronjobs']);
        Route::get('/{id}/edit', \Wikichua\Bliss\Http\Livewire\Admin\Cronjob\Editing::class)->name('edit')
            ->middleware(['can:update-cronjobs']);
    });

    Route::middleware(['can:read-reports'])->prefix('report')->name('report.')->group(function () {
        Route::get('/', \Wikichua\Bliss\Http\Livewire\Admin\Report\Listing::class)->name('list');
        Route::get('/{id}/show', \Wikichua\Bliss\Http\Livewire\Admin\Report\Showing::class)->name('show');
        Route::get('/create', \Wikichua\Bliss\Http\Livewire\Admin\Report\Creating::class)->name('create')
            ->middleware(['can:create-reports']);
        Route::get('/{id}/edit', \Wikichua\Bliss\Http\Livewire\Admin\Report\Editing::class)->name('edit')
            ->middleware(['can:update-reports']);
    });

    Route::middleware(['can:read-logs'])->prefix('log')->name('log.')->group(function () {
        Route::get('/', \Wikichua\Bliss\Http\Livewire\Admin\Log\Listing::class)->name('list');
        Route::get('/{id}/show', \Wikichua\Bliss\Http\Livewire\Admin\Log\Showing::class)->name('show');
    });

    Route::middleware(['can:read-versionizers'])->prefix('versionizer')->name('versionizer.')->group(function () {
        Route::get('/', \Wikichua\Bliss\Http\Livewire\Admin\Versionizer\Listing::class)->name('list');
        Route::get('/{id}/show', \Wikichua\Bliss\Http\Livewire\Admin\Versionizer\Showing::class)->name('show');
    });

    Route::middleware(['can:read-queuejobs'])->prefix('queuejob')->name('queuejob.')->group(function () {
        Route::get('/', \Wikichua\Bliss\Http\Livewire\Admin\QueueJob\Listing::class)->name('list');
        Route::get('/{id}/show', \Wikichua\Bliss\Http\Livewire\Admin\QueueJob\Showing::class)->name('show');
    });

    Route::middleware(['can:read-failedjobs'])->prefix('failedjob')->name('failedjob.')->group(function () {
        Route::get('/', \Wikichua\Bliss\Http\Livewire\Admin\FailedJob\Listing::class)->name('list');
        Route::get('/{id}/show', \Wikichua\Bliss\Http\Livewire\Admin\FailedJob\Showing::class)->name('show');
    });
});
