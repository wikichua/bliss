<?php

namespace Wikichua\Bliss\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class Installation extends Command
{
    protected $signature = 'bliss:install {--no-compiled}';

    protected $description = 'Bliss Installation';

    public function __construct()
    {
        parent::__construct();

        $this->vendorPath = base_path('vendor/wikichua/bliss');
        if (is_dir(base_path('packages/Wikichua/Bliss'))) {
            $this->vendorPath = base_path('packages/Wikichua/Bliss');
        } elseif (is_dir(base_path('packages/wikichua/bliss'))) {
            $this->vendorPath = base_path('packages/wikichua/bliss');
        }
    }

    public function handle()
    {
        $vendorPath = $this->vendorPath;

        File::ensureDirectoryExists(resource_path('views/vendor/bliss/layouts'));

        if (Str::contains($this->vendorPath, 'packages')) {
            $files = [
                $vendorPath.'/dev.webpack.mix.js' => base_path('webpack.mix.js'),
            ];
            $this->copiesFileOrDirectory($files);

            File::delete(base_path('package.json'));
            File::link($vendorPath.'/package.json', base_path('package.json'));
            $this->info('Created symlink '.$vendorPath.'/package.json to '. base_path('package.json'));
            $this->newLine();

            File::delete(base_path('tailwind.config.js'));
            File::link($vendorPath.'/tailwind.config.js', base_path('tailwind.config.js'));
            $this->info('Created symlink '.$vendorPath.'/tailwind.config.js to '. base_path('tailwind.config.js'));
            $this->newLine();

            File::delete(resource_path('views/vendor/bliss/layouts/navigation.blade.php'));
            File::link($vendorPath.'/resources/views/layouts/navigation.blade.php', resource_path('views/vendor/bliss/layouts/navigation.blade.php'));
            $this->info('Created symlink '.$vendorPath.'/resources/views/layouts/navigation.blade.php to '. resource_path('views/vendor/bliss/layouts/navigation.blade.php'));
            $this->newLine();

            File::delete(resource_path('views/vendor/bliss/layouts/header.blade.php'));
            File::link($vendorPath.'/resources/views/layouts/header.blade.php', resource_path('views/vendor/bliss/layouts/header.blade.php'));
            $this->info('Created symlink '.$vendorPath.'/resources/views/layouts/header.blade.php to '. resource_path('views/vendor/bliss/layouts/header.blade.php'));
            $this->newLine();
        } else {
            $files = [
                $vendorPath.'/package.json' => base_path('package.json'),
                $vendorPath.'/tailwind.config.js' => base_path('tailwind.config.js'),
                $vendorPath.'/webpack.mix.js' => base_path('webpack.mix.js'),
                $vendorPath.'/resources/css' => resource_path('css'),
                $vendorPath.'/resources/js' => resource_path('js'),
            ];
            $this->copiesFileOrDirectory($files);
            $this->copiesFileOrDirectory([
                $vendorPath.'/resources/views/layouts/header.blade.php' => resource_path('views/vendor/bliss/layouts/header.blade.php'),
                $vendorPath.'/resources/views/layouts/navigation.blade.php' => resource_path('views/vendor/bliss/layouts/navigation.blade.php'),
            ], false);
        }

        $this->checkCacheDriver();
        $this->replaceRouteServiceProviderHomeConst();
        $this->replaceUserModelExtends();
        $this->injectRunCronjobsCallIntoConsoleKernel();
        $this->injectUseArtisanTraitIntoConsoleKernel();
        $this->injectDisableCommandsCallConsoleKernel();
        $this->injectMixAppKeyInEnvFile();
        $this->injectMongoConfigIntoDatabaseConfigFile();
        $this->removeDefaultWebRoute();
        $this->publishLaravelStubsAndReplaceSomething();
        $this->injectPublishLivewireAssetsIntoComposerJson();
        if ($this->option('no-compiled') != true) {
            $this->dumpComposer();
            if ($this->confirm('npm install?', true)) {
                $output = shell_exec('npm install');
                $this->info($output);
            }
            if ($this->confirm('npm run prod?', true)) {
                $output = shell_exec('npm run prod');
                $this->info($output);
            }
        }
        cache()->flush();
        return ;
    }

    protected function dumpComposer()
    {
        $output = shell_exec('composer dump');
        $this->info($output);
    }

    protected function checkCacheDriver()
    {
        if (in_array(config('cache.default'), ['file'])) {
            $file = config_path('cache.php');
            $content = @File::get($file);
            if (!str_contains($content, '\'default\' => env(\'CACHE_DRIVER\', \'array\'),')) {
                $lines = explode(PHP_EOL, $content);
                foreach ($lines as $key => $line) {
                    if (str_contains($line, '\'default\' => env(\'CACHE_DRIVER\', \'file\'),')) {
                        $from = $line;
                        $to = $lines[$key] = str_repeat("\t", 1).'\'default\' => env(\'CACHE_DRIVER\', \'array\'),';
                    }
                }
                if (isset($from)) {
                    @File::replace($file, implode(PHP_EOL, $lines));
                    $this->info('Replace '.trim($from).' to '. trim($to) . ' in ' . $file);
                    $this->newLine();
                }
            }
        }
        $file = base_path('.env');
        $content = @File::get($file);
        if (str_contains($content, 'CACHE_DRIVER=file')) {
            $lines = explode(PHP_EOL, $content);
            foreach ($lines as $key => $line) {
                if (str_contains($line, 'CACHE_DRIVER=file')) {
                    $from = $line;
                    $to = $lines[$key] = 'CACHE_DRIVER=array';
                }
            }
            if (isset($from)) {
                @File::replace($file, implode(PHP_EOL, $lines));
                $this->info('Replace '.trim($from).' to '. trim($to) . ' in ' . $file);
                $this->newLine();
            }
        }
    }

    protected function copiesFileOrDirectory(array $data, $replace = true)
    {
        foreach ($data as $from => $to) {
            if ($replace == false && file_exists($to)) {
                return;
            }
            is_dir($from)? @File::copyDirectory($from, $to):@File::copy($from, $to);
            $this->info('Copy '.$from.' to '. $to);
            $this->newLine();
        }
    }

    protected function replaceRouteServiceProviderHomeConst()
    {
        $file = app_path('Providers/RouteServiceProvider.php');
        $lines = explode(PHP_EOL, @File::get($file));
        foreach ($lines as $key => $line) {
            if (str_contains($line, 'public const HOME') && !str_contains($line, "public const HOME = '/';")) {
                $from = $line;
                $to = $lines[$key] = "\tpublic const HOME = '/';";
            }
        }
        if (isset($from) && '' != $from) {
            @File::replace($file, implode(PHP_EOL, $lines));
            $this->info('Replace '.trim($from).' to '. trim($to) . ' in ' . $file);
            $this->newLine();
        }
    }

    protected function replaceUserModelExtends()
    {
        $file = app_path('Models/User.php');
        $content = @File::get($file);
        if (!str_contains($content, 'class User extends \Wikichua\Bliss\Models\User')) {
            $lines = explode(PHP_EOL, $content);
            foreach ($lines as $key => $line) {
                if (str_contains($line, 'class User extends Authenticatable')) {
                    $from = $line;
                    $to = $lines[$key] = str_replace('Authenticatable', '\Wikichua\Bliss\Models\User', $line);
                }
            }
            if (isset($from)) {
                @File::replace($file, implode(PHP_EOL, $lines));
                $this->info('Replace '.trim($from).' to '. trim($to) . ' in ' . $file);
                $this->newLine();
            }
        }
        $file = app_path('Models/User.php');
        $content = @File::get($file);
        if (!str_contains($content, '\'created_by\'') && !str_contains($content, '\'updated_by\'')) {
            $lookFor = <<<EOL
                    'name',
                    'email',
                    'password',
            EOL;
            $replaceStr = <<<EOL
                    'name',
                    'email',
                    'password',
                    'avatar',
                    'timezone',
                    'status',
                    'created_by',
                    'updated_by',
            EOL;
            $lines = explode(PHP_EOL, $content);
            $content = str_replace($lookFor, $replaceStr, $content);
            @File::replace($file, $content);
        }
    }

    protected function injectMongoConfigIntoDatabaseConfigFile()
    {
        $configStr = <<<EOL

                'mongodb' => [
                    'driver' => 'mongodb',
                    'host' => env('MDB_HOST', '127.0.0.1'),
                    'port' => env('MDB_PORT', 27017),
                    'database' => env('MDB_DATABASE', 'homestead'),
                    'username' => env('MDB_USERNAME', 'homestead'),
                    'password' => env('MDB_PASSWORD', 'secret'),
                    'options' => [
                        // here you can pass more settings to the Mongo Driver Manager
                        // see https://www.php.net/manual/en/mongodb-driver-manager.construct.php under "Uri Options" for a list of complete parameters that you can use
                        'database' => env('MDB_AUTHENTICATION_DATABASE', 'admin'), // required with Mongo 3+
                    ],
                ],
        EOL;
        $file = base_path('config/database.php');
        $content = @File::get($file);
        if (!str_contains($content, '\'mongodb\' => [')) {
            $lines = explode(PHP_EOL, $content);
            foreach ($lines as $key => $line) {
                if (str_contains($line, '\'connections\' => [')) {
                    $from = $line;
                    $to = $lines[$key] = $line.PHP_EOL.str_repeat("\t", 2).$configStr;
                }
            }
            if (isset($from)) {
                @File::replace($file, implode(PHP_EOL, $lines));
                $this->info('Replace '.trim($from).' to '. trim($to) . ' in ' . $file);
                $this->newLine();
            }
        }
    }

    protected function injectRunCronjobsCallIntoConsoleKernel()
    {
        $file = app_path('Console/Kernel.php');
        $content = @File::get($file);
        if (!str_contains($content, '$this->runCronjobs($schedule);')) {
            $lines = explode(PHP_EOL, $content);
            foreach ($lines as $key => $line) {
                if (str_contains($line, '$schedule->command(\'inspire\')->hourly();')) {
                    $from = $line;
                    $to = $lines[$key] = $line.PHP_EOL.str_repeat("\t", 2).'$this->runCronjobs($schedule);';
                }
            }
            if (isset($from)) {
                @File::replace($file, implode(PHP_EOL, $lines));
                $this->info('Replace '.trim($from).' to '. trim($to) . ' in ' . $file);
                $this->newLine();
            }
        }
    }

    protected function injectUseArtisanTraitIntoConsoleKernel()
    {
        $file = app_path('Console/Kernel.php');
        $content = @File::get($file);
        if (!str_contains($content, 'use \Wikichua\Bliss\Traits\ArtisanTrait;')) {
            $lines = explode(PHP_EOL, $content);
            foreach ($lines as $key => $line) {
                if (str_contains($line, 'protected function schedule(Schedule $schedule)')) {
                    $from = $line;
                    $to = $lines[$key] = str_repeat("\t", 1).'use \Wikichua\Bliss\Traits\ArtisanTrait;'.PHP_EOL.str_repeat("\t", 1).'protected $commands_disabled = [
        \'production\' => [\'migrate:fresh\',\'migrate:refresh\',\'migrate:reset\',\'bliss:install\'],
    ];'.PHP_EOL.$line;
                }
            }
            if (isset($from)) {
                @File::replace($file, implode(PHP_EOL, $lines));
                $this->info('Replace '.trim($from).' to '. trim($to) . ' in ' . $file);
                $this->newLine();
            }
        }
    }

    protected function injectDisableCommandsCallConsoleKernel()
    {
        $file = app_path('Console/Kernel.php');
        $content = @File::get($file);
        if (!str_contains($content, '$this->disableCommands();')) {
            $lines = explode(PHP_EOL, $content);
            foreach ($lines as $key => $line) {
                if (str_contains($line, '$this->load(__DIR__.\'/Commands\');')) {
                    $from = $line;
                    $to = $lines[$key] = str_repeat("\t", 2).'$this->disableCommands();'.PHP_EOL.$line;
                }
            }
            if (isset($from)) {
                @File::replace($file, implode(PHP_EOL, $lines));
                $this->info('Replace '.trim($from).' to '. trim($to) . ' in ' . $file);
                $this->newLine();
            }
        }
    }
    protected function injectMixAppKeyInEnvFile()
    {
        $files = [base_path('.env.example'), base_path('.env')];
        foreach ($files as $file) {
            $content = @File::get($file);
            if (!str_contains($content, 'MIX_APP_KEY')) {
                $lines = explode(PHP_EOL, $content);
                foreach ($lines as $key => $line) {
                    if (str_contains($line, 'APP_DEBUG')) {
                        $from = $line;
                        $to = $lines[$key] = 'MIX_APP_KEY="${APP_KEY}"'.PHP_EOL.$line;
                    }
                }
                if (isset($from)) {
                    @File::replace($file, implode(PHP_EOL, $lines));
                    $this->info('Added '. trim($to) . ' in ' . $file);
                    $this->newLine();
                }
            }
        }
    }
    protected function removeDefaultWebRoute()
    {
        $file = base_path('routes/web.php');
        $content = @File::get($file);
        $lines = explode(PHP_EOL, $content);
        foreach ($lines as $key => $line) {
            if (Str::contains($line, 'Route::get(\'/\', function () {')) {
                $string = $lines[$key].PHP_EOL.$lines[$key + 1].PHP_EOL.$lines[$key + 2];
                unset($lines[$key], $lines[$key + 1], $lines[$key + 2]);
            }
        }
        if (isset($string)) {
            @File::replace($file, implode(PHP_EOL, $lines));
            $this->info('Removed '.$string);
            $this->newLine();
        }
    }
    protected function injectPublishLivewireAssetsIntoComposerJson()
    {
        $this->call('vendor:publish', [
            '--force' => true,
            '--ansi' => true,
            '--tag' => 'livewire:assets',
        ]);
        $content = @File::get(base_path('composer.json'));
        if (!Str::contains($content, '"@php artisan vendor:publish --force --tag=livewire:assets --ansi"')) {
            $content = str_replace('"@php artisan package:discover --ansi"',
                '"@php artisan package:discover --ansi",'.PHP_EOL."\t\t\t".'"@php artisan vendor:publish --force --tag=livewire:assets --ansi"', $content);
            @File::replace(base_path('composer.json'), $content);
        }
    }
    protected function publishLaravelStubsAndReplaceSomething()
    {
        $this->call('stub:publish');
        $this->call('livewire:stubs');
        foreach (File::files(base_path('stubs')) as $file) {
            $content = @File::get($file->getPathname());
            if (Str::contains($content, 'use Illuminate\Bus\Queueable;') && !Str::contains($content, 'use Wikichua\Bliss\Traits\Queueable;')) {
                $content = str_replace('use Illuminate\Bus\Queueable;', 'use Wikichua\Bliss\Traits\Queueable;', $content);
                @File::replace($file, $content);
            }
        }

        $livewireStubContent = @File::get(base_path('stubs/livewire.stub'));
        if (!Str::contains($livewireStubContent, 'use \Wikichua\Bliss\Traits\ComponentTraits;')) {
            $livewireStubContent = str_replace('use Livewire\Component;',
                'use Livewire\Component;'.PHP_EOL.'use \Wikichua\Bliss\Traits\ComponentTraits;', $livewireStubContent);
            @File::replace(base_path('stubs/livewire.stub'), $livewireStubContent);
        }
        if (!Str::contains($livewireStubContent, 'use ComponentTraits;')) {
            $livewireStubContent = str_replace('public function render()',
                "\t".'use ComponentTraits;'.PHP_EOL.PHP_EOL."\t".'public function render()', $livewireStubContent);
            @File::replace(base_path('stubs/livewire.stub'), $livewireStubContent);
        }

        $modelStubContent = @File::get(base_path('stubs/model.stub'));
        if (!Str::contains($modelStubContent, 'use Wikichua\Bliss\Casts\UserTimezone;')) {
            $modelStubContent = str_replace('use Illuminate\Database\Eloquent\Model;',
                'use Illuminate\Database\Eloquent\Model;'.PHP_EOL.'use Wikichua\Bliss\Casts\UserTimezone;', $modelStubContent);
            @File::replace(base_path('stubs/model.stub'), $modelStubContent);
        }
        if (!Str::contains($modelStubContent, 'use Wikichua\Bliss\Traits\AllModelTraits;')) {
            $modelStubContent = str_replace('use Illuminate\Database\Eloquent\Model;',
                'use Illuminate\Database\Eloquent\Model;'.PHP_EOL.'use Wikichua\Bliss\Traits\AllModelTraits;', $modelStubContent);
            @File::replace(base_path('stubs/model.stub'), $modelStubContent);
        }
        $eol = <<<EOL
            protected \$casts = [
                'created_at' => UserTimezone::class,
                'updated_at' => UserTimezone::class,
            ];
        EOL;
        if (!Str::contains($modelStubContent, $eol)) {
            $modelStubContent = str_replace('use HasFactory;',
                'use HasFactory;'.PHP_EOL.$eol, $modelStubContent);
            @File::replace(base_path('stubs/model.stub'), $modelStubContent);
        }
    }
}
