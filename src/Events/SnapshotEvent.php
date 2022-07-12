<?php

namespace Wikichua\Bliss\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class SnapshotEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        protected Model $model,
        protected null|string $mode = null,
        protected array|Collection|null $before = [],
        protected array|Collection|null $after = []
    ) {
    }

    public function getModel()
    {
        return $this->model;
    }

    public function getMode()
    {
        return $this->mode;
    }

    public function getOriginal()
    {
        return $this->before;
    }

    public function getChanges()
    {
        return $this->after;
    }
}
