<?php

namespace App\Observers;

use App\Models\OnlineEvent;
use App\Models\OnlineEventUser;
use App\Models\User;
use App\Notifications\CandidateAssignedToOnlineEventNotification;

class OnlineEventUserObserver
{
    /**
     * Handle the online event user "created" event.
     *
     * @param  \App\Models\OnlineEventUser  $onlineEventUser
     * @return void
     */
    public function created(OnlineEventUser $onlineEventUser)
    {
        $user = User::find($onlineEventUser->user_id);
        $onlineEvent = OnlineEvent::find($onlineEventUser->online_event_id);

        if ($user->isCandidate()) {
            $user->notify(new CandidateAssignedToOnlineEventNotification($user, $onlineEvent));
        }
    }

    /**
     * Handle the online event user "updated" event.
     *
     * @param  \App\Models\OnlineEventUser  $onlineEventUser
     * @return void
     */
    public function updated(OnlineEventUser $onlineEventUser)
    {
        //
    }

    /**
     * Handle the online event user "deleted" event.
     *
     * @param  \App\Models\OnlineEventUser  $onlineEventUser
     * @return void
     */
    public function deleted(OnlineEventUser $onlineEventUser)
    {
        //
    }

    /**
     * Handle the online event user "restored" event.
     *
     * @param  \App\Models\OnlineEventUser  $onlineEventUser
     * @return void
     */
    public function restored(OnlineEventUser $onlineEventUser)
    {
        //
    }

    /**
     * Handle the online event user "force deleted" event.
     *
     * @param  \App\Models\OnlineEventUser  $onlineEventUser
     * @return void
     */
    public function forceDeleted(OnlineEventUser $onlineEventUser)
    {
        //
    }
}
