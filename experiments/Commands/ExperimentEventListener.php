<?php

namespace Wikichua\Bliss\Exp\Commands;

use Illuminate\Console\Command;
use Wikichua\Bliss\Events\AuditEvent;
use Wikichua\Bliss\Events\SnapshotEvent;
use Wikichua\Bliss\Exp\Models\ExperimentKeywordsModel;

class ExperimentEventListener extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'experiment:eventlistener';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Experiment Event Listener';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $model = ExperimentKeywordsModel::create([
            'keyword' => randomWords(),
        ]);
        $oldKeyword = $model->keyword;

        ExperimentKeywordsModel::where('id', $model->id)->first()->update(['keyword' => randomWords(),]);
        $newKeyword = randomWords();
        ExperimentKeywordsModel::where('id', $model->id)->update([
            'keyword' => $newKeyword,
        ]);

        // $model = ExperimentKeywordsModel::find($model->id);
        // AuditEvent::dispatch($model, 'updated');
        // SnapshotEvent::dispatch($model, 'updated', $before = [], $after = []);

        // dd($oldKeyword);
    }
}
