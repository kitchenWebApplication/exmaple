<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Users\ProfileUpdateRequest;
use Auth;

class ProfileController extends ApiController
{
    /**
     * Get the user's profile data.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $user = Auth::user();

        return response()->json(compact('user'));
    }

    /**
     * Update the user's profile data.
     *
     * @param ProfileUpdateRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ProfileUpdateRequest $request)
    {
        $model = Auth::user();
        $model->fill($request->all());

        if ($model->save()) {
            return response()->jsonSuccess(trans('events.updated'), $model);
        }

        return response()->jsonError();
    }
}
