<?php

namespace Stock\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Stock\Fetchers\FetchResult;

class ImportComplete
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        private FetchResult $result
    )
    {
    }

    public function getResult(): FetchResult
    {
        return $this->result;
    }
}
