<?php

namespace App\Http\Controllers\Api\Orders;

use App\Events\OrderStatusUpdated;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Orders\OrderCreateRequest;
use App\Http\Requests\Orders\OrderUpdateStatusRequest;
use App\Models\Order;
use Auth;

class OrderController extends ApiController
{
    /**
     * Get all orders.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $query = Order::with('user')->has('user');

        // Query for user with role "waiter"
        if (Auth::user()->hasRole('waiter')) {
            $query->where('user_id', Auth::id());
        }

        $orders = $query->orderBy('status_id')->latest()->get();

        return response()->json(compact('orders'));
    }

    /**
     * Create an order.
     *
     * @param OrderCreateRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(OrderCreateRequest $request)
    {
        // Create an order
        $model = new Order();
        $model->fill($request->all());
        $model->user_id = Auth::id();

        if ($model->save()) {
            $model->load('user');

            event(new OrderStatusUpdated($model, Auth::id()));

            return response()->jsonSuccess(trans('events.created'), $model);
        }

        return response()->jsonError();
    }

    /**
     * Delete an order by id.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id)
    {
        $model = Order::find($id);

        // Check an access
        $this->authorize($model);

        // Delete an order
        if (!Order::destroy($id)) {
            return response()->jsonError();
        }

        return response()->jsonSuccess(trans('events.deleted'));
    }

    /**
     * Update a cookie time and status of order.
     *
     * @param int                      $id
     * @param OrderUpdateStatusRequest $request
     *
     * @return mixed
     */
    public function updateStatus(int $id, OrderUpdateStatusRequest $request)
    {
        $model = Order::find($id);

        // Check an access
        $this->authorize('updateStatus', $model);

        // Fill the data
        if ($time = $request->input('time')) {
            $model->cooking_time = $time;
        }
        $model->status_id = $request->input('status');

        if ($model->save()) {
            $model->load('user');

            event(new OrderStatusUpdated($model, Auth::id()));

            return response()->jsonSuccess(trans('events.updated'), $model);
        }

        return response()->jsonError();
    }
}
