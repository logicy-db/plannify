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
        return in_array($user->role_id, [Role::EVENT_ORGANIZER, Role::ADMIN]);
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
        return in_array($user->role_id, [Role::EVENT_ORGANIZER, Role::ADMIN]) &&
            $event->exists;
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
        return in_array($user->role_id, [Role::EVENT_ORGANIZER, Role::ADMIN]) &&
            $event->exists;
    }

    /**
     * Determine whether user can participate in the event.
     *
     * @param User $user
     * @param Event $event
     * @return bool
     */
    public function participate(User $user, Event $event) {
        return !$event->users->contains($user->id) &&
            !$event->isFull();
    }

    /**
     * Determine whether user can cancel his/her participation in the event.
     *
     * @param User $user
     * @param Event $event
     * @return bool
     */
    public function cancelParticipation(User $user, Event $event) {
        return $event->usersGoing()->contains($user->id) &&
            $event->isPlanned();
    }

    /**
     * Determine whether user can cancel other user participation in the event.
     *
     * @param User $user
     * @param Event $event
     * @param User $model
     * @return bool
     */
    public function cancelUserParticipation(User $user, Event $event, User $model) {
        return in_array($user->role_id, [Role::EVENT_ORGANIZER, Role::ADMIN]) &&
            $event->exists &&
            $model->exists &&
            $event->usersGoing()->contains($model->id);
    }

    /**
     * Determine whether user can queue for the event.
     *
     * @param User $user
     * @param Event $event
     * @return bool
     */
    public function queue(User $user, Event $event) {
        return !$event->users->contains($user->id) &&
            $event->isFull() &&
            $event->isPlanned();
    }

    /**
     * Determine whether user can cancel his/her queue in event.
     *
     * @param User $user
     * @param Event $event
     * @return bool
     */
    public function cancelQueue(User $user, Event $event) {
        return $event->usersQueued()->contains($user->id) &&
            $event->isPlanned();
    }

    /**
     * Determine whether user can cancel other user queue in event.
     *
     * @param User $user
     * @param Event $event
     * @param User $model
     * @return bool
     */
    public function cancelUserQueue(User $user, Event $event, User $model) {
        return $model->exists &&
            $event->exists &&
            $event->usersQueued()->contains($model->id) &&
            in_array($user->role_id, [Role::EVENT_ORGANIZER, Role::ADMIN]);
    }

    /**
     * Determine whether user can allow user participation after blocking in the event.
     *
     * @param User $user
     * @param Event $event
     * @param User $model
     * @return bool
     */
    public function allowParticipation(User $user, Event $event, User $model) {
        return $event->exists &&
            $event->usersCanceled()->contains($model->id) &&
            in_array($user->role_id, [Role::EVENT_ORGANIZER, Role::ADMIN]);
    }
}
