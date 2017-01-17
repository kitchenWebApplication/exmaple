<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    /**
     * @param User  $user
     * @param Order $order
     *
     * @return bool
     */
    public function create(User $user, Order $order)
    {
        return $user->hasRole('waiter');
    }

    /**
     * @param User  $user
     * @param Order $order
     *
     * @return bool
     */
    public function updateStatus(User $user, Order $order)
    {
        return
            $user->hasRole('manager')
            && $order->status_id !== Order::STATUS_PREPARED;
    }

    /**
     * @param User  $user
     * @param Order $order
     *
     * @return bool
     */
    public function delete(User $user, Order $order)
    {
        return
            $user->hasRole('waiter')
            && $user->id === $order->user_id
            && $order->status_id === Order::STATUS_CREATED;
    }
}
