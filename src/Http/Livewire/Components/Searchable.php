<?php

namespace Wikichua\Bliss\Http\Livewire\Components;

use Livewire\Component;

class Searchable extends Component
{
    use \Livewire\WithPagination;

    public $query = '';

    protected $queryString = [
        'page' => ['as' => 'sp']
    ];
    protected $listeners = [];

    public function render()
    {
        $searchables = null;
        if ($this->query != '') {
            $searchables = app(config('bliss.Models.Searchable'))->query()->filterTags($this->query)->paginate(3);
            foreach ($searchables as $item) {
                $item->data = collect([
                    'title' => basename(str_replace('\\', '/', $item->model)),
                    'url' => app($item->model)->find($item->model_id)->readUrl ?? '#',
                    'timestamp' => $item->updated_at ?? $item->created_at ?? '',
                ]);
            }
        }
        return view('bliss::livewire.searchable', compact('searchables'));
    }
}
