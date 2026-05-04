<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StockChangeEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $store_item_id;
    public $store_item_qty;

    public function __construct($store_item_id,$store_item_qty)
    {
        $this->store_item_id=$store_item_id;
        $this->store_item_qty=$store_item_qty;
    }
    public function broadcastOn(): array
    {
        return ['stock-change-channel'];
    }

    public function broadcastAs(){
        return 'stock-change-event';
    }
}
