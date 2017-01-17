<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Users\UserCreateRequest;
use App\Http\Requests\Users\UserUpdateRequest;
use App\Models\User;

class UserController extends ApiController
{
    /**
     * Get all users.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $users = User::all();

        return response()->json(compact('users'));
    }

    /**
     * Get the user by id.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id)
    {
        $user = User::where('id', $id)->first();

        if (!$user) {
            return response()->jsonNotFound();
        }

        return response()->json(compact('user'));
    }

    /**
     * Create a user.
     *
     * @param UserCreateRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(UserCreateRequest $request)
    {
        // Create a user
        $model = new User();
        $model->fill($request->all());
        $model->role_id = $request->input('role_id');
        $model->password = bcrypt($request->input('password'));

        if ($model->save()) {
            return response()->jsonSuccess(trans('events.created'), $model);
        }

        return response()->jsonError();
    }

    /**
     * Update a user by id.
     *
     * @param int               $id
     * @param UserUpdateRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(int $id, UserUpdateRequest $request)
    {
        // Update a user
        $model = User::find($id);
        $model->fill($request->all());
        $model->role = $request->input('role');

        if ($password = $request->input('password')) {
            $model->password = bcrypt($password);
        }

        if ($model->save()) {
            return response()->jsonSuccess(trans('events.updated'), $model);
        }

        return response()->jsonError();
    }

    /**
     * Delete a user by id.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id)
    {
        $user = User::find($id);

        // Check an access
        $this->authorize($user);

        // Delete a user
        if (!$user->delete()) {
            return response()->jsonError();
        }

        return response()->jsonSuccess(trans('events.deleted'));
    }
}
