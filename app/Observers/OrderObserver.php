<?php

namespace App\Observers;

use App\Models\Order;

class OrderObserver
{
    /**
     * Listen to the Order creating event.
     *
     * @param Order $model
     *
     * @return void
     */
    public function creating(Order $model)
    {
        $model->status_id = Order::STATUS_CREATED;
    }
}