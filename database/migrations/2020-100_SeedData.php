<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up()
    {
        // create default admin user
        $user = app(config('bliss.Models.User'))->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'),
            'status' => 'A',
        ]);
        $user_id = $user->id;
        $user->update([
            'created_by' => $user_id,
            'updated_by' => $user_id,
        ]);
        $this->updateAdminIdToEnvFile(base_path('.env'), $user_id);
        $this->updateAdminIdToEnvFile(base_path('.env.example'), null);
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
            'status' => 'A',
        ]);

        $permission = app(config('bliss.Models.Permission'));
        $permission->createGroup('Audits', ['read-audits', 'delete-audits'], $user_id);
        $permission->createGroup('Admin Panel', ['access-admin-panel'], $user_id);
        $permission->createGroup('Permissions', ['create-permissions', 'read-permissions', 'update-permissions', 'delete-permissions', 'replicate-permissions', 'bulk-delete-permissions'], $user_id);
        $permission->createGroup('Roles', ['create-roles', 'read-roles', 'update-roles', 'delete-roles', 'bulk-delete-roles'], $user_id);
        $permission->createGroup('Users', ['create-users', 'read-users', 'Update Users', 'delete-users', 'update-users-password', 'bulk-delete-users', 'impersonate-users'], $user_id);
        $permission->createGroup('Settings', ['create-settings', 'read-settings', 'update-settings', 'delete-settings', 'bulk-delete-settings'], $user_id);
        $permission->createGroup('Reports', ['create-reports', 'read-reports', 'update-reports', 'delete-reports', 'bulk-delete-reports', 'export-reports'], $user_id);
        $permission->createGroup('Versionizers', ['read-versionizers', 'revert-versionizers', 'delete-versionizers', 'bulk-delete-versionizers'], $user_id);
        $permission->createGroup('Logs', ['read-logs', 'delete-logs', 'bulk-delete-logs'], $user_id);
        $permission->createGroup('Cronjobs', ['create-cronjobs', 'read-cronjobs', 'update-cronjobs', 'delete-cronjobs', 'bulk-delete-cronjobs'], $user_id);
        if (Schema::hasTable('personal_access_tokens')) {
            $permission->createGroup('Personal Access Token', ['create-personal-access-token', 'read-personal-access-token', 'delete-personal-access-token', 'bulk-delete-personal-access-token'], $user_id);
        }
        $permission->createGroup('Queue Jobs', ['read-queuejobs', 'delete-queuejobs', 'bulk-delete-queuejobs'], $user_id);
        $permission->createGroup('Failed Jobs', ['read-failedjobs', 'retry-failedjobs', 'delete-failedjobs', 'bulk-retry-failedjobs', 'bulk-delete-failedjobs'], $user_id);

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
        $setting->create(['created_by' => $user_id, 'updated_by' => $user_id, 'key' => 'log_level', 'value' => ['emergency' => 'Emergency', 'alert' => 'Alert', 'critical' => 'Critical', 'error' => 'Error', 'warning' => 'Warning', 'notice' => 'Notice', 'info' => 'Info', 'debug' => 'Debug']]);
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

    protected function updateAdminIdToEnvFile($file, $id = null)
    {
        $change = false;
        $content = @File::get($file);
        if (! str_contains($content, 'ADMIN_ID')) {
            $lines = explode(PHP_EOL, $content);
            foreach ($lines as $key => $line) {
                if (str_contains($line, 'DB_CONNECTION')) {
                    $change = true;
                    $lines[$key] = 'ADMIN_ID='.PHP_EOL.$line;
                    if (! blank($id)) {
                        $lines[$key] = 'ADMIN_ID="'.$id.'"'.PHP_EOL.$line;
                    }
                }
            }
        } else {
            $lines = explode(PHP_EOL, $content);
            foreach ($lines as $key => $line) {
                if (str_contains($line, 'ADMIN_ID')) {
                    $change = true;
                    $lines[$key] = 'ADMIN_ID=';
                    if (! blank($id)) {
                        $lines[$key] = 'ADMIN_ID="'.$id.'"';
                    }
                }
            }
        }
        if ($change) {
            @File::replace($file, implode(PHP_EOL, $lines));
        }
    }
};
