<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class OrderStatusUpdated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    /**
     * @var Order
     */
    public $order;

    /**
     * @var int
     */
    public $user_id;

    /**
     * @param Order $order
     * @param int   $user_id
     */
    public function __construct(Order $order, int $user_id)
    {
        $this->order = $order;
        $this->user_id = $user_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('orders');
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        $this->order->user = $this->order->user;

        return [
            'order'   => $this->order,
            'user_id' => $this->user_id
        ];
    }
}
