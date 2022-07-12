<?php

namespace Wikichua\Bliss\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class Searchable extends Command
{
    protected $signature = 'bliss:searchable {chunk=1000}';

    protected $description = 'Indexing searchable';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $chunk = $this->argument('chunk');
        $this->info('Indexing to Searchable');
        $models = getModelsList();
        $excludeMongoDb = false;
        if (! \File::exists(base_path('vendor/jenssegers/mongodb'))) {
            $excludeMongoDb = true;
        }
        app(config('bliss.Models.Searchable'))->truncate();
        $searchable = app(config('bliss.Models.Searchable'))->query();
        foreach ($models as $model) {
            if ($excludeMongoDb && Str::contains($model, 'Wikichua\Bliss\Models\Mongodb') || in_array($model, config('bliss.searchable.exceptions'))) {
                continue;
            }
            $hasTable = false;
            try {
                $table = app($model)->query()->getModel()->getTable();
                $hasTable = Schema::hasTable($table);
            } catch (\Illuminate\Database\QueryException $e) {
            }

            if ($hasTable && count(app($model)->toSearchableFieldsArray()) && $count = app($model)->query()->count()) {
                $this->info("\n".$model);
                $bar = $this->output->createProgressBar($count);
                $bar->start();
                app($model)->query()->orderBy('id')->chunk($chunk, function ($results) use ($searchable, $bar) {
                    foreach ($results as $result) {
                        $searchable->create([
                            'model' => $result->searchableModelAs(),
                            'model_id' => $result->id,
                            'tags' => $result->toSearchableFieldsArray(),
                        ]);
                        $bar->advance();
                    }
                });
                $bar->finish();
            }
        }
        $this->info("\nIndexing Completed");
        cache()->flush();
    }
}
