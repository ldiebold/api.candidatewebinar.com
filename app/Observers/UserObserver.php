<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Str;
use App\Notifications\SendCandidateAccountPasswordNotification;

class UserObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function created(User $user)
    {
        if ($user->isCandidate()) {
            $password = Str::random(8);
            $user->password = bcrypt($password);
            $user->save();
            $user->notify(new SendCandidateAccountPasswordNotification($user, $password));
        }

        return $user;
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function updated(User $user)
    {
        //
    }

    /**
     * Handle the user "deleting" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function deleting(User $user)
    {
        $user->online_events()->detach();
    }

    /**
     * Handle the user "restored" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the user "force deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}
