<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserRecordDeleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $date;
    public $gender;
    /**
     * Create a new event instance.
     */
    public function __construct($date, $gender)
    {
        $this->date = $date;
        $this->gender = $gender;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
