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
            ->get()
            ->each(function (OnlineEvent $onlineEvent) {
                $onlineEvent->archive();
            });
    }
}
