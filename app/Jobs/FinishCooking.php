<?php

namespace App\Jobs;

use App\Events\OrderStatusUpdated;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class FinishCooking implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Order
     */
    protected $order;

    /**
     * @var int
     */
    protected $user_id;

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
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->order->status_id != Order::STATUS_PREPARING) {
            return;
        }

        $this->order->status_id = Order::STATUS_PREPARED;
        $this->order->save();

        event(new OrderStatusUpdated($this->order, $this->user_id));
    }
}
