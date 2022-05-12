<?php

namespace Wikichua\Bliss\Traits;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait ComponentTraits
{
    use WithPagination;
    use WithFileUploads;
    use AuthorizesRequests;

    /* DO NOT declare protected listeners and queryString in this traits */
    public $pageOptions = [5, 10,  25,  50,  100,  200];
    public $sorts = [];
    public $take = 25;
    public $filters = [];
    public $cols = [];
    public Builder|Model $model;
    public $infoData = [];

    protected $reauthEnabled = false;
    protected $bulkActionEnabled = true;
    protected $bulkActionKey = 'id';
    public $bulkSelectedKeys = [];

    public function bootComponentTraits()
    {
        $this->viewShare();
        // $this->listeners[] = 'onDelete';
        $this->queryString['take'] = ['as' => 't'];
        $this->queryString['filters'] = ['as' => 'f'];
        $this->queryString['sorts'] = ['as' => 's'];
        if (isset($this->page)) { // from \Livewire\WithPagination
            $this->queryString['page'] = ['as' => 'p'];
        }
    }

    public function viewShare()
    {
        view()->share('headerTitle', $this->headerTitle);
        view()->share('reauthEnabled', $this->reauthEnabled);
        view()->share('bulkActionEnabled', $this->bulkActionEnabled);
        view()->share('bulkActionKey', $this->bulkActionKey);
    }

    public function getListeners()
    {
        $listeners = array_merge($this->listeners, [
            'refresh' => '$refresh',
            'filterDatatable' => '$refresh'
        ]);
        return $listeners;
    }

    /*public function getQueryString()
    {
        $predefined = [
            'take' => ['as' => 't'],
            'filters' => ['as' => 'f'],
            'sorts' => ['as' => 's'],
        ];
        if (isset($this->page)) { // from \Livewire\WithPagination
            $predefined['page'] = ['as' => 'p'];
        }
        $queryString = array_merge($this->queryString, $predefined);
        return $queryString;
    }*/

    public function paginationView()
    {
        return 'bliss::livewire.pagination';
    }

    public function resetFilters()
    {
        $this->filters = [];
    }

    public function castModelToProperty(Builder|Model $model = null, array $only = [])
    {
        $model = $model ??= $this->model;
        if ($model instanceOf Model) {
            $this->model = $model;
            $properties = collect($this->model->toArray());
        } else {
            $model = $model->getModel();
            $fillables = $model->getFillable();
            $fillablesValues = array_map(fn () => null, $fillables);
            $properties = collect(array_combine($fillables, $fillablesValues));
        }
        if (!empty($only)) {
            $properties = $properties->only($only);
        }
        foreach ($properties as $key => $value) {
            if ($key == 'id') {
                $key = 'primary_key_id';
            }
            data_set($this, $key, $value);
        }
        $this->getInfoData();
        return $this;
    }

    protected function getInfoData()
    {
        if ($this->model ?? null) {
            $this->infoData = [
                'Created At' => $this->model?->created_at ?? null,
                'Created By' => $this->model?->creator?->name ?? null,
                'Updated At' => $this->model?->updated_at ?? null,
                'Updated By' => $this->model?->modifier?->name ?? null,
            ];
        }
    }

    public function flashStatusSession(string $message, string $eventOrSession = 'event')
    {
        if (strtolower($eventOrSession) == 'event') {
            $this->dispatchBrowserEvent('flash-status', ['status' => $message]);
        } elseif (strtolower($eventOrSession) == 'session') {
            session()->flash('status', $message);
        } else { // url
            $redirect = $eventOrSession;
            return redirect()->to($redirect)->with('status', $message);
        }
    }

    public function alertNotify(string $message, string $permissionString, string $link = ''): void
    {
        sendAlertNotification(
            message: $message,
            sender: auth()->id(),
            receivers: userIdsWithPermission($permissionString),
            link: $link,
            icon: '',
        );
    }
}
