<?php

namespace App\Console\Commands;

use App\Models\OnlineEvent;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class FillMissingOnlineEventUidsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:fill-missing-online-event-uids';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix blank UIDs for old Online Events';

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
        OnlineEvent::where('uid', "")
            ->get()
            ->each(function ($onlineEvent) {
                $onlineEvent->uid = Str::uuid();
                $onlineEvent->save();
            });
    }
}
