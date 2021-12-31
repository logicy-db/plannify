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
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserInvitation  $userInvitation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, UserInvitation $userInvitation)
    {
        //
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

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserInvitation  $userInvitation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, UserInvitation $userInvitation)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserInvitation  $userInvitation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, UserInvitation $userInvitation)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserInvitation  $userInvitation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, UserInvitation $userInvitation)
    {
        //
    }
}
