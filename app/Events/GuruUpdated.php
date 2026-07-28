<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GuruUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $action;
    public $guruName;

    public function __construct(string $action, string $guruName)
    {
        $this->action = $action; // 'created', 'updated', 'deleted'
        $this->guruName = $guruName;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('guru-updates'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'guru.updated';
    }
}
