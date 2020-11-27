<?php

namespace App\Console\Commands;

use App\Models\OnlineEvent;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Vonage\Numbers\Number;

class CheckInformationSessionWithinTimeframe extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tester {minutessBeforeStart} {minutesBeforeEnd}';

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
        $minutessBeforeStart = (int) $this->arguments()["minutessBeforeStart"];
        $minutesBeforeEnd = (int) $this->arguments()["minutesBeforeEnd"];

        $eventsXMinutesUntilStartTime = collect();
        $eventsLessThanXMinutesUntilFinish = collect();

        $now = Carbon::now();

        $onlineEventsNotEnded = OnlineEvent::whereTime('end_time', '>', $now)->whereDate('end_time', '>=', $now)->get();
        $onlineEventsNotStarted = OnlineEvent::whereTime('start_time', '>', $now)->whereDate('start_time', '>=', $now)->get();

        $eventsXMinutesUntilStartTime = $onlineEventsNotStarted->filter(function ($onlineEvent) use ($minutessBeforeStart, $now) {
            $diffInMinutes = $onlineEvent->start_time->diffInMinutes($now);
            return $diffInMinutes < $minutessBeforeStart;
        });

        return 0;
    }
}
