<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if auth user is not given user
     *
     * @param User $authUser
     * @param User $givenUser
     *
     * @return bool
     */
    public function delete(User $authUser, User $givenUser)
    {
        return $authUser->id !== $givenUser->id;
    }
}
