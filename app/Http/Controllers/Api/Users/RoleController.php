<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\ApiController;
use App\Models\User;

class RoleController extends ApiController
{
    /**
     * Get the role list.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $roles = User::roles();

        return response()->json(compact('roles'));
    }
}
