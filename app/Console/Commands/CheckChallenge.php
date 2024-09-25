<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Challenge;
use App\Helpers\ChallengeHelper;

class CheckChallenge extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'city-partner:check-challenge';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        foreach(Challenge::where('status', Challenge::CHALLENGING)->get() as $challenge) {
            ChallengeHelper::checkSuccess($challenge);
        }
    }
}
