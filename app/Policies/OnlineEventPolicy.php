<?php

namespace App\Policies;

use App\Models\OnlineEvent;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OnlineEventPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
        // return $user->role === 'ibo';
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\OnlineEvent  $onlineEvent
     * @return mixed
     */
    public function view(User $user, OnlineEvent $onlineEvent)
    {
        if ($user->isIbo() || $user->isAdmin()) {
            return true;
        }

        if ($user->online_events->contains($onlineEvent->id)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\OnlineEvent  $onlineEvent
     * @return mixed
     */
    public function update(User $user, OnlineEvent $onlineEvent)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\OnlineEvent  $onlineEvent
     * @return mixed
     */
    public function delete(User $user, OnlineEvent $onlineEvent)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\OnlineEvent  $onlineEvent
     * @return mixed
     */
    public function restore(User $user, OnlineEvent $onlineEvent)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\OnlineEvent  $onlineEvent
     * @return mixed
     */
    public function forceDelete(User $user, OnlineEvent $onlineEvent)
    {
        return false;
    }
}
