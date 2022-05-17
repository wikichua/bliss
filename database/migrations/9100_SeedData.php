<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        $user_id = 1;
        // create default admin user
        $user = app(config('bliss.Models.User'))->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'),
            'type' => 'Admin',
            'status' => 'A',
            'created_by' => $user_id,
            'updated_by' => $user_id,
        ]);
        // create default admin role
        app(config('bliss.Models.Role'))->create([
            'name' => 'Admin',
            'admin' => true,
            'created_by' => $user_id,
            'updated_by' => $user_id,
        ]);
        // give default admin user default admin role
        $user->roles()->attach(app(config('bliss.Models.Role'))->where('admin', true)->first()->id);

        app(config('bliss.Models.User'))->create([
            'created_by' => $user_id,
            'updated_by' => $user_id,
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => Hash::make('admin123'),
            'status' => 'A'
        ]);

        $permission = app(config('bliss.Models.Permission'));
        $permission->createGroup('Audits', ['read-audits', 'delete-audits'], $user_id);
        $permission->createGroup('Admin Panel', ['access-admin-panel'], $user_id);
        $permission->createGroup('Permissions', ['create-permissions', 'read-permissions', 'update-permissions', 'delete-permissions', 'replicate-permissions', 'bulk-delete-permissions'], $user_id);
        $permission->createGroup('Roles', ['create-roles', 'read-roles', 'update-roles', 'delete-roles', 'bulk-delete-roles'], $user_id);
        $permission->createGroup('Users', ['create-users', 'read-users', 'Update Users', 'delete-users', 'update-users-password', 'bulk-delete-users', 'impersonate-users'], $user_id);
        $permission->createGroup('Settings', ['create-settings', 'read-settings', 'update-settings', 'delete-settings', 'bulk-delete-settings'], $user_id);
        $permission->createGroup('Reports', ['create-reports', 'read-reports', 'update-reports', 'delete-reports', 'bulk-delete-reports', 'export-reports'], $user_id);
        $permission->createGroup('Versionizers', ['read-versionizers', 'revert-versionizers', 'delete-versionizers', 'bulk-delete-versionizers'], 1);
        $permission->createGroup('Cronjobs', ['create-cronjobs', 'read-cronjobs', 'update-cronjobs', 'delete-cronjobs', 'bulk-delete-cronjobs'], 1);
        if (Schema::hasTable('personal_access_tokens')) {
            $permission->createGroup('Personal Access Token', ['create-personal-access-token', 'read-personal-access-token', 'delete-personal-access-token', 'bulk-delete-personal-access-token'], $user_id);
        }
        $permission->createGroup('Queue Jobs', ['read-queuejobs', 'delete-queuejobs', 'bulk-delete-queuejobs'], 1);
        $permission->createGroup('Failed Jobs', ['read-failedjobs', 'retry-failedjobs', 'delete-failedjobs', 'bulk-retry-failedjobs', 'bulk-delete-failedjobs'], 1);


        $setting = app(config('bliss.Models.Setting'));
        $setting->create([
            'created_by' => $user_id, 'updated_by' => $user_id,
            'key' => 'permission_groups',
            'value' => ['Admin Panel' => 'Admin Panel', 'Permission' => 'Permission', 'Setting' => 'Setting', 'Role' => 'Role', 'User' => 'User', 'Audit' => 'Audit', 'Failed Jobs' => 'Failed Jobs', 'Queue Jobs' => 'Queue Jobs'],
        ]);
        $setting->create(['created_by' => $user_id, 'updated_by' => $user_id, 'key' => 'locales', 'value' => ['en' => 'EN']]);
        $setting->create(['created_by' => $user_id, 'updated_by' => $user_id, 'key' => 'user_status', 'value' => ['A' => 'Active', 'I' => 'Inactive']]);
        $setting->create(['created_by' => $user_id, 'updated_by' => $user_id, 'key' => 'report_status', 'value' => ['A' => 'Active', 'I' => 'Inactive']]);
        $setting->create(['created_by' => $user_id, 'updated_by' => $user_id, 'key' => 'cronjob_status', 'value' => ['A' => 'Active', 'I' => 'Inactive']]);
        $setting->create(['created_by' => $user_id, 'updated_by' => $user_id, 'key' => 'queuejob_status', 'value' => ['W' => 'Waiting', 'P' => 'Processing', 'C' => 'Completed', 'E' => 'Error']]);
        $setting->create(['created_by' => $user_id, 'updated_by' => $user_id, 'key' => 'max_workers', 'value' => 5]);

        app(config('bliss.Models.Cronjob'))->query()->create([
            'name' => 'Inspire',
            'command' => 'inspire',
            'frequency' => 'daily',
            'status' => 'A',
        ]);
        app(config('bliss.Models.Cronjob'))->query()->create([
            'name' => 'Prune Batches',
            'command' => 'queue:prune-batches',
            'frequency' => 'daily',
            'status' => 'A',
        ]);

        \Artisan::call('bliss:searchable');
    }

    public function down()
    {
        app(config('bliss.Models.Permission'))->whereIn('group', [
            'Audits',
            'Admin Panel',
            'Roles',
            'Users',
            'Personal Access Token',
            'Permissions',
            'Reports',
            'Settings',
            'Cronjobs',
        ])->delete();

        app(config('bliss.Models.Setting'))->whereIn('key', [
            'report_status',
            'cronjob_status',
            'permission_groups',
            'user_types',
            'user_status',
            'locales',
        ])->delete();
    }
};
