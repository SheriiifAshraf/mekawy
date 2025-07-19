<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VideoViewed
{
    use Dispatchable, SerializesModels;

    public $videoId;
    public $studentId;

    /**
     * Create a new event instance.
     *
     * @param int $videoId
     * @param int $studentId
     */
    public function __construct(int $videoId, int $studentId)
    {
        $this->videoId = $videoId;
        $this->studentId = $studentId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('channel-name');
    }
}
