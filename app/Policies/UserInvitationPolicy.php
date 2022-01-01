<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;
use App\Models\UserInvitation;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserInvitationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return in_array($user->role_id, [Role::HUMAN_RESOURCES, Role::ADMIN]);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return in_array($user->role_id, [Role::HUMAN_RESOURCES, Role::ADMIN]);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserInvitation  $userInvitation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, UserInvitation $userInvitation)
    {
        // TODO: REFACTOR ALL - use exists in every policy
        return $userInvitation->exists &&
            in_array($user->role_id, [Role::HUMAN_RESOURCES, Role::ADMIN]);
    }
}
