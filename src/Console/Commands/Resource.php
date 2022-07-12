<?php

namespace Wikichua\Bliss\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class Resource extends Command
{
    protected $signature = 'bliss:resource {cmd=config} {module?} {--table=}';

    protected $description = 'Make Admin Resource';

    protected $placeholders = [
        'migration' => true,
        'tableName' => '',
    ];

    protected $componentPath;

    protected $configPath;

    protected $modelPath;

    protected $module;

    protected $requestPath;

    protected $resourceConfig = [];

    protected $stubsPath;

    protected $viewPath;

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $cmd = $this->argument('cmd');
        if (! in_array(Str::lower($cmd), ['config', 'make'])) {
            $cmd = $this->choice(
                'Which 1 again?',
                ['config', 'make'],
                0
            );
        }
        $module = $this->argument('module');
        if (blank($module)) {
            $module = $this->ask('What is the module name?');
        }

        $this->module = Str::studly(Str::singular($module));

        $this->configPath = resource_path('bliss');
        File::ensureDirectoryExists($this->configPath);

        $this->stubsPath = base_path('vendor/wikichua/bliss/stubs');

        $this->placeholders['tableName'] = ! blank($this->option('table')) ? $this->option('table') : Str::plural(Str::snake($this->module));

        switch ($cmd) {
            case 'config':
                $this->makeConfig();
                break;

            default: // make
                $this->makeResource();
                break;
        }

        $this->info('Module: '.$this->module);
    }

    protected function makeResource()
    {
        $configFile = Str::of('?/?.php')->replaceArray('?', [$this->configPath, $this->module]);
        if (! File::exists($configFile)) {
            $this->error($configFile.' not found!');

            return;
        }
        $this->resourceConfig = collect(require $configFile);

        $this->placeholders['lower'] = Str::lower($this->module);
        $this->placeholders['singular'] = Str::singular($this->module);
        $this->placeholders['lower-singular'] = Str::lower(Str::singular($this->module));
        $this->placeholders['plural'] = Str::plural($this->module);
        $this->placeholders['lower-plural'] = Str::lower(Str::plural($this->module));
        $this->placeholders['model'] = $this->module;
        $this->placeholders['headerTitle'] = implode(' ', Str::ucsplit($this->module.'Management'));
        $this->placeholders['namespace'] = Str::of('?Http\Livewire\Admin\?')->replaceArray('?', [app()->getNamespace(), $this->module]);
        $this->placeholders['requestNamepace'] = Str::of('?Http\Requests\Admin')->replaceArray('?', [app()->getNamespace()]);
        $this->placeholders['moduleRequest'] = Str::of('?Request')->replaceArray('?', [$this->module]);
        $this->placeholders['modelNamespace'] = Str::of('?Models\Admin')->replaceArray('?', [app()->getNamespace()]);

        $form = $this->resourceConfig->get('form', []);
        $tableSetup = [];
        foreach ($form as $fieldName => $data) {
            $component = trim($data['component']);
            $searchable = trim($data['searchable']);
            $required = trim($data['required']);
            $fieldName = trim($fieldName);

            // migration
            $migration = $data['migration'];
            $column = $migration['column'];
            $nullable = $migration['nullable'] ? '->nullable()' : '';
            $index = $migration['index'] ? '->index()' : '';
            $useCurrent = '';
            $default = ! blank($migration['default']) ? '->default(\''.$migration['default'].'\')' : '';
            if (in_array($column, ['date', 'dateTime', 'dateTimeTz', 'time', 'timeTz'])) {
                $useCurrent = $migration['useCurrent'] ? '->useCurrent()' : '';
            }
            $tableSetup[] = <<<EOL
            \$table->$column('$fieldName')$default$nullable$index$useCurrent;
            EOL;

            // Model & Request
            $fillablesStr[] = Str::of('\'?\'')->replaceArray('?', [$fieldName]);
            if ($searchable) {
                $searchablesStr[] = Str::of('\'?\'')->replaceArray('?', [$fieldName]);
            }
            if ($required) {
                $requiredsStr[] = Str::of('\'?\' => \'required\'')->replaceArray('?', [$fieldName]);
            }

            // Listing
            $label = $data['label'];
            $sortable = is_bool($data['sortable']) && $data['sortable'] ? 'true' : 'false';
            $listingStr[] = <<<EOL
            ['title' => '$label', 'data' => '$fieldName', 'sortable' => $sortable]
            EOL;

            // crud
            switch ($component) {
                case '':
                    break;

                default:
                        $formComponentStr = <<<EOL
                        <x-bliss::form-input type="$component" wire:model.defer="$fieldName" label="$label" />
                        EOL;
                        $formDisabledComponentStr = <<<EOL
                        <x-bliss::form-input type="$component" wire:model.defer="$fieldName" label="$label" disabled />
                        EOL;
                        $searchComponentStr = <<<EOL
                        <x-bliss::search-input type="text" id="$fieldName" label="$label" wire:model.defer="filters.$fieldName" />
                        EOL;
                    break;
            }
            $formStr[] = $formComponentStr;
            $formDisabledStr[] = $formDisabledComponentStr;
            if ($searchable) {
                $searchStr[] = $searchComponentStr;
            }
        }

        // migration
        $this->placeholders['tableSetup'] = implode(PHP_EOL.Str::repeat("\t", 3), $tableSetup);

        // Listing
        $this->placeholders['listingStr'] = implode(','.PHP_EOL.Str::repeat("\t", 3), $listingStr);

        // crud
        $this->placeholders['formStr'] = implode(PHP_EOL.Str::repeat("\t", 4), $formStr);
        $this->placeholders['formDisabledStr'] = implode(PHP_EOL.Str::repeat("\t", 4), $formDisabledStr);
        $this->placeholders['searchStr'] = implode(PHP_EOL.Str::repeat("\t", 7), $searchStr);

        // Model
        $fillableStr = implode(','.PHP_EOL.Str::repeat("\t", 2), $fillablesStr);
        $this->placeholders['fillable'] = <<<EOL
        protected \$fillable = [
                $fillableStr
            ];
        EOL;
        $searchableStr = implode(','.PHP_EOL.Str::repeat("\t", 2), $searchablesStr);
        $this->placeholders['searchableFields'] = <<<EOL
        protected \$searchableFields = [
                $searchableStr
            ];
        EOL;
        $requiredStr = implode(','.PHP_EOL.Str::repeat("\t", 3), $requiredsStr);
        $this->placeholders['requiredStr'] = <<<EOL
        return [
                    $requiredStr
                ];
        EOL;

        $this->componentPath = app_path('Http/Livewire/Admin/'.$this->module);
        File::ensureDirectoryExists($this->componentPath);
        $this->viewPath = resource_path('views/livewire/admin/'.$this->placeholders['lower']);
        File::ensureDirectoryExists($this->viewPath);

        $this->makeMigration();

        $this->makeComponent('Component');
        $this->makeComponent('Creating');
        $this->makeComponent('Editing');
        $this->makeComponent('Listing');
        $this->makeComponent('Showing');

        $this->makeViews('actions');
        $this->makeViews('create');
        $this->makeViews('edit');
        $this->makeViews('list');
        $this->makeViews('show');

        $this->modelPath = app_path('Models/Admin');
        File::ensureDirectoryExists($this->modelPath);
        $this->makeModel();

        $this->requestPath = app_path('Http/Requests/Admin');
        File::ensureDirectoryExists($this->requestPath);
        $this->makeRequest();

        $this->makeRoute();
        $this->makeNavigation();
    }

    protected function makeNavigation()
    {
        $file = Str::of(resource_path('views/vendor/bliss/layouts/?.blade.php'))->replaceArray('?', ['resource_nav']);
        $fileContent = File::get($file);
        $singular = $this->placeholders['singular'];
        $lowerSingular = $this->placeholders['lower-singular'];
        $lowerPlural = $this->placeholders['lower-plural'];
        $activeStrCheck = <<<EOL
        '$lowerSingular.*',
        EOL;
        $activeStr = <<<EOL
        '$lowerSingular.*',
                {{--KeepMeHerePlease activeStr--}}
        EOL;
        $canStrCheck = <<<EOL
        'read-$lowerPlural',
        EOL;
        $canStr = <<<EOL
        'read-$lowerPlural',
                {{--KeepMeHerePlease canStr--}}
        EOL;
        $linkStrCheck = <<<EOL
        <x-bliss::dropdown-link :href="route('$lowerSingular.list')" :active="request()->routeIs('$lowerSingular.*')" can="read-$lowerPlural">
        EOL;
        $linkStr = <<<EOL
        <x-bliss::dropdown-link :href="route('$lowerSingular.list')" :active="request()->routeIs('$lowerSingular.*')" can="read-$lowerPlural">
                    {{ __('$singular') }}
                </x-bliss::dropdown-link>
                {{--KeepMeHerePlease linkStr--}}
        EOL;

        if (! Str::contains($fileContent, $activeStrCheck)) {
            $fileContent = str_replace('{{--KeepMeHerePlease activeStr--}}', $activeStr, $fileContent);
        }
        if (! Str::contains($fileContent, $canStrCheck)) {
            $fileContent = str_replace('{{--KeepMeHerePlease canStr--}}', $canStr, $fileContent);
        }
        if (! Str::contains($fileContent, $linkStrCheck)) {
            $fileContent = str_replace('{{--KeepMeHerePlease linkStr--}}', $linkStr, $fileContent);
        }

        File::put($file, $fileContent);
        $this->line('resource_nav file updated: <info>'.$file.'</info>');
    }

    protected function makeRequest()
    {
        $stub = Str::of('?/Request.stub')->replaceArray('?', [$this->stubsPath]);
        $file = Str::of($this->requestPath.'/?Request.php')->replaceArray('?', [$this->module]);

        $stubContent = File::get($stub);
        $stubContent = $this->getReplacers($stubContent);
        File::put($file, $stubContent);
        $this->line($this->module.' file created: <info>'.$file.'</info>');
    }

    protected function makeModel()
    {
        $stub = Str::of('?/Model.stub')->replaceArray('?', [$this->stubsPath]);
        $file = Str::of($this->modelPath.'/?.php')->replaceArray('?', [$this->module]);

        $stubContent = File::get($stub);
        $stubContent = $this->getReplacers($stubContent);
        File::put($file, $stubContent);
        $this->line($this->module.' file created: <info>'.$file.'</info>');
    }

    protected function makeViews($filename = 'actions')
    {
        $stub = Str::of('?/?.blade.stub')->replaceArray('?', [$this->stubsPath, $filename]);
        $file = Str::of($this->viewPath.'/?.blade.php')->replaceArray('?', [$filename]);

        $stubContent = File::get($stub);
        $stubContent = $this->getReplacers($stubContent);
        File::put($file, $stubContent);
        $this->line($filename.' file created: <info>'.$file.'</info>');
    }

    protected function makeRoute()
    {
        $stub = Str::of('?/route.stub')->replaceArray('?', [$this->stubsPath]);
        $file = Str::of(base_path('/routes/?.php'))->replaceArray('?', ['bliss']);
        $fileContent = File::get($file);
        $stubContent = File::get($stub);
        $stubContent = $this->getReplacers($stubContent);
        $str = <<<EOL
        $stubContent
            /*KeepMeHerePlease*/
        EOL;
        if (! Str::contains($fileContent, $stubContent)) {
            $fileContent = str_replace('/*KeepMeHerePlease*/', $str, $fileContent);
            File::put($file, $fileContent);
            $this->line('bliss file updated: <info>'.$file.'</info>');
        } else {
            $str = $stubContent;
            $this->error('bliss file not updated: <info>'.$file.'</info>');
            $this->line('please manually remove this string >>> '.$str);
        }
    }

    protected function makeComponent($filename = 'Component')
    {
        $stub = Str::of('?/?.stub')->replaceArray('?', [$this->stubsPath, $filename]);
        $file = Str::of($this->componentPath.'/?.php')->replaceArray('?', [$filename]);

        $stubContent = File::get($stub);
        $stubContent = $this->getReplacers($stubContent);
        File::put($file, $stubContent);
        $this->line($filename.' file created: <info>'.$file.'</info>');
    }

    protected function makeMigration()
    {
        if ($this->resourceConfig->get('migration', false)) {
            $stub = Str::of('?/migration.stub')->replaceArray('?', [$this->stubsPath]);
            $postfixFileName = Str::of('_create_?_table.php')->replaceArray('?', [$this->placeholders['tableName']]);
            $migrationsFiles = File::files(base_path('database/migrations'));
            $file = Str::of(base_path('database/migrations/?_create_?_table.php'))->replaceArray('?', [date('Y_m_d_000000'), $this->placeholders['tableName']]);
            foreach ($migrationsFiles as $migrationsFile) {
                if (Str::endsWith($migrationsFile->getBasename(), $postfixFileName)) {
                    $file = $migrationsFile->getPathname();
                }
            }
            $stubContent = File::get($stub);
            $stubContent = $this->getReplacers($stubContent);
            File::put($file, $stubContent);
            $this->line('Migration file created: <info>'.$file.'</info>');
        }
    }

    protected function makeConfig()
    {
        $stub = Str::of('?/config.stub')->replaceArray('?', [$this->stubsPath]);
        $configFile = Str::of('?/?.php')->replaceArray('?', [$this->configPath, $this->module]);
        if (File::exists($configFile) &&
            ! $this->confirm(Str::of('? exists. Do you wish to continue?')->replaceArray('?', [$configFile]))
        ) {
            return;
        }
        $stubContent = File::get($stub);
        $stubContent = $this->getReplacers($stubContent);
        File::put($configFile, $stubContent);
        $this->line('Config file created: <info>'.$configFile.'</info>');
    }

    protected function getReplacers($content)
    {
        $placeholders = collect($this->placeholders);
        $placeholders = $placeholders->mapWithKeys(function ($item, $key) {
            $item = ! blank($this->{$key} ?? null) ? $this->{$key} : $item;
            if (is_bool($item)) {
                $item = $item ? 'true' : 'false';
            }
            $mapKey = '['.$key.']';

            return [
                $mapKey => $item,
            ];
        });

        return str_replace($placeholders->keys()->toArray(), $placeholders->values()->toArray(), $content);
    }
}
