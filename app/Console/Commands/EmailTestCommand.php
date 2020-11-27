<?php

namespace App\Console\Commands;

use App\Models\OnlineEvent;
use App\Models\User;
use App\Notifications\CandidateAssignedToOnlineEventNotification;
use App\Notifications\SendCandidateAccountPasswordNotification;
use Illuminate\Console\Command;

class EmailTestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        User::first()->notify(new SendCandidateAccountPasswordNotification(
            User::first(),
            'SoDangSecret'
        ));

        return 0;
    }
}
