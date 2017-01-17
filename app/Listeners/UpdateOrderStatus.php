<?php

namespace App\Listeners;

use App\Events\OrderStatusUpdated;
use App\Jobs\FinishCooking;
use App\Models\Order;
use Carbon\Carbon;

class UpdateOrderStatus
{
    /**
     * Handle the event.
     *
     * @param  OrderStatusUpdated $event
     *
     * @return void
     */
    public function handle(OrderStatusUpdated $event)
    {
        $order = $event->order;

        if ($order->status_id == Order::STATUS_PREPARING) {
            $time = Carbon::createFromFormat('Y-m-d H:i:s', $order->cooked_at);

            $job = (new FinishCooking($order, $event->user_id))->delay($time);

            dispatch($job);
        }
    }
}
