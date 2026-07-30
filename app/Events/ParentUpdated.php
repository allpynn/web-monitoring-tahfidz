<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ParentUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $action;
    public $parentName;

    public function __construct(string $action, string $parentName)
    {
        $this->action = $action; // 'created', 'updated', 'deleted'
        $this->parentName = $parentName;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('parent-updates'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'parent.updated';
    }
}
