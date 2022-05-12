<?php

namespace Wikichua\Bliss\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Database\Eloquent\Model;

class AuditEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(protected Model $model, protected null|string $mode = null)
    {
    }

    public function getModel()
    {
        return $this->model;
    }

    public function getMode()
    {
        return $this->mode;
    }
}
