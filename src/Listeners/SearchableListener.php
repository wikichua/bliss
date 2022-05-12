<?php

namespace Wikichua\Bliss\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Wikichua\Bliss\Events\SearchableEvent;
use Wikichua\Bliss\Traits\Searchable;

class SearchableListener
{
    use Searchable;

    protected $event = null;
    protected $model = null;

    public function __construct()
    {
        //
    }

    public function handle(SearchableEvent $event)
    {
        $this->event = $event;
        $this->model = $event->getModel();
        $snapshot = $this->model->getSnapshot();
        $mode = $this->event->getMode();
        switch ($mode) {
            case 'created':
            case 'restored':
                $this->create();
                break;
            case 'updated':
                $this->update();
                break;
            case 'deleted':
            case 'forceDeleted':
                $this->delete();
                break;
        }
    }

    protected function searchableModelAs()
    {
        $model = $this->model;
        return $model::class;
    }

    protected function getSearchableFields()
    {
        return $this->model->getSearchableFields();
    }

    protected function toSearchableFieldsArray()
    {
        $array = [];
        if (!\Str::contains($this->searchableModelAs(), config('bliss.searchable.exceptions'))) {
            if (!blank($this->getSearchableFields())) {
                foreach ($this->getSearchableFields() as $field) {
                    $array[$field] = $this->model->attributes[$field] ?? $this->model->{$field};
                }
            } else {
                $array = $this->model->toArray();
            }
        }
        return $array;
    }

    protected function create()
    {
        if (!blank($this->toSearchableFieldsArray())) {
            $searchable = app(config('bliss.Models.Searchable'))->query()->create([
                'model' => $this->searchableModelAs(),
                'model_id' => $this->model->id,
                'tags' => $this->toSearchableFieldsArray(),
            ]);
        }
    }

    protected function update()
    {
        if (!blank($this->toSearchableFieldsArray())) {
            $searchable = app(config('bliss.Models.Searchable'))
                ->query()
                ->where('model', $this->searchableModelAs())
                ->where('model_id', $this->model->id);

            $searchable = $searchable->update([
                'model' => $this->searchableModelAs(),
                'model_id' => $this->model->id,
                'tags' => $this->toSearchableFieldsArray(),
            ]);
        }
    }

    protected function delete()
    {
        if (!blank($this->toSearchableFieldsArray())) {
            $searchable = app(config('bliss.Models.Searchable'))
                ->where('model', $this->searchableModelAs())
                ->where('model_id', $this->model->id);

            $searchable->delete();
        }
    }
}
