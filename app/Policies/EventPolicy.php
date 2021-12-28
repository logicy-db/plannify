<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class EventPolicy
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
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Event $event)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return in_array($user->role_id, [Role::EVENT_ORGANIZER, Role::HUMAN_RESOURCES, Role::ADMIN]);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Event $event)
    {
        return in_array($user->role_id, [Role::EVENT_ORGANIZER, Role::HUMAN_RESOURCES, Role::ADMIN]) &&
            !is_null($event);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Event $event)
    {
        return in_array($user->role_id, [Role::EVENT_ORGANIZER, Role::HUMAN_RESOURCES, Role::ADMIN]) &&
            !is_null($event);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Event $event)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Event $event)
    {
        //
    }

    public function participate(User $user, Event $event) {
        return !$event->users->contains($user->id) && !$event->isFull();
    }

    /**
     * User cancels his/her own participation in event.
     *
     * @param User $user
     * @param Event $event
     * @return bool
     */
    public function cancelParticipation(User $user, Event $event) {
        // TODO: check time before event starts
        return $event->usersGoing()->contains($user->id);
    }

    /**
     * Event organizer, HR or admin cancels other user participation in event.
     *
     * @param User $user
     * @param Event $event
     * @param User $model
     * @return bool
     */
    public function cancelUserParticipation(User $user, Event $event, User $model) {
        return in_array($user->role_id, [Role::EVENT_ORGANIZER, Role::HUMAN_RESOURCES, Role::ADMIN]) &&
            !is_null($event) &&
            $model->exists;
    }

    public function queue(User $user, Event $event) {
        return !$event->usersCanceled()->contains($user->id) &&
            !$event->usersQueued()->contains($user->id) &&
            $event->isFull();
    }

    public function cancelQueue(User $user, Event $event) {
        return $event->usersQueued()->contains($user->id);
    }

    public function cancelUserQueue(User $user, Event $event, User $model) {
        return $event->usersQueued()->contains($model) &&
            in_array($user->role_id, [Role::EVENT_ORGANIZER, Role::HUMAN_RESOURCES, Role::ADMIN]);
    }

    public function allowParticipation(User $user, Event $event, User $model) {
        return !is_null($event) &&
            $event->usersCanceled()->contains($model->id) &&
            in_array($user->role_id, [Role::EVENT_ORGANIZER, Role::HUMAN_RESOURCES, Role::ADMIN]);
    }
}
