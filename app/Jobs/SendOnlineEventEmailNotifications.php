<?php

namespace App\Jobs;

use App\Models\OnlineEvent;
use App\Notifications\EventStartingSoonNotification;
use Database\Seeders\OnlineEventSeeder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendOnlineEventEmailNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $onlineEvents = OnlineEvent::where('email_notifications_sent', 0)
            ->startsBetweenNowAndXMinutesBeforeStartTime(15)
            ->get();

        info($onlineEvents);

        $onlineEvents->each(function ($onlineEvent) {
            $onlineEvent->email_notifications_sent = true;
            $onlineEvent->save();

            $onlineEvent->users->each(function ($user) use ($onlineEvent) {
                $user->notify(new EventStartingSoonNotification($onlineEvent));
            });
        });
    }
}
