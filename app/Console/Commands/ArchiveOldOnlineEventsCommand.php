<?php

namespace App\Console\Commands;

use App\Models\OnlineEvent;
use Illuminate\Console\Command;

class ArchiveOldOnlineEventsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'online-events:archive-old {minutes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Archive old online events';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        OnlineEvent::endedMoreThanXMinutesAgo($this->argument('minutes'))
            ->where('archived', false)
            ->get()
            ->each(function (OnlineEvent $onlineEvent) {
                $this->handleHasRecurrence($onlineEvent);
                $onlineEvent->archive();
            });
    }

    /**
     * Replicate the given OnlineEvent if it has a recurrence value
     * and set its times based on the value of recurrence
     *
     * @param OnlineEvent $onlineEvent
     * @return void
     */
    public function handleHasRecurrence(OnlineEvent $onlineEvent)
    {
        $recurrencesMappedToPlural = collect([
            'daily' => 'days',
            'weekly' => 'weeks',
            'fortnightly' => 'fortnights',
            'monthly' => 'months',
            'yearly' => 'years'
        ]);

        if ($onlineEvent->recurrence && $recurrencesMappedToPlural->has($onlineEvent->recurrence)) {
            $replicatedOnlineEvent = $onlineEvent->replicate();
            $replicatedOnlineEvent->start_time = $onlineEvent->start_time->add(1, $recurrencesMappedToPlural[$onlineEvent->recurrence]);
            $replicatedOnlineEvent->end_time = $onlineEvent->end_time->add(1, $recurrencesMappedToPlural[$onlineEvent->recurrence]);
            $replicatedOnlineEvent->save();
        }
    }
}
