<?php

namespace App\Observers;

use App\Models\OnlineEvent;
use Illuminate\Support\Str;

class OnlineEventObserver
{
    /**
     * Handle the OnlineEvent "created" event.
     *
     * @param  \App\Models\OnlineEvent  $onlineEvent
     * @return void
     */
    public function created(OnlineEvent $onlineEvent)
    {
        //
    }

    /**
     * Handle the OnlineEvent "created" event.
     *
     * @param  \App\Models\OnlineEvent  $onlineEvent
     * @return void
     */
    public function creating(OnlineEvent $onlineEvent)
    {
        $onlineEvent->uid = Str::uuid();
    }

    /**
     * Handle the OnlineEvent "updated" event.
     *
     * @param  \App\Models\OnlineEvent  $onlineEvent
     * @return void
     */
    public function updated(OnlineEvent $onlineEvent)
    {
        //
    }

    /**
     * Handle the OnlineEvent "deleting" event.
     *
     * @param  \App\Models\OnlineEvent  $onlineEvent
     * @return void
     */
    public function deleting(OnlineEvent $onlineEvent)
    {
        $onlineEvent->users()->detach();
    }

    /**
     * Handle the OnlineEvent "restored" event.
     *
     * @param  \App\Models\OnlineEvent  $onlineEvent
     * @return void
     */
    public function restored(OnlineEvent $onlineEvent)
    {
        //
    }

    /**
     * Handle the OnlineEvent "force deleted" event.
     *
     * @param  \App\Models\OnlineEvent  $onlineEvent
     * @return void
     */
    public function forceDeleted(OnlineEvent $onlineEvent)
    {
        //
    }
}
