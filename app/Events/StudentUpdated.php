<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StudentUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $action;
    public $studentName;
    public $nis;

    public function __construct(string $action, string $studentName, ?string $nis = null)
    {
        $this->action = $action; // 'created', 'updated', 'deleted'
        $this->studentName = $studentName;
        $this->nis = $nis;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('student-updates'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'student.updated';
    }
}
