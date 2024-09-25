<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\CrowdFunding;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CrowdFunding>
 */
class CrowdFundingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = array_keys(\App\Models\CrowdFunding::statusOptions());
        $status = $statuses[rand(0, count($statuses)-1)];
        // const APPLYING = 'applying';
        // const WAITING = 'waiting';
        // const USING = 'using';
        // const COMPLETED = 'completed';
        // const CANCELED = 'canceled';
        if (in_array($status, [CrowdFunding::APPLYING, CrowdFunding::WAITING, CrowdFunding::CANCELED])) {
            $success_at = null;
            $returned_at = null;
        }elseif ($status == CrowdFunding::USING){
            $success_at = today()->subDay(rand(1,20))->addHours(rand(1,20))->addMinutes(rand(2,55));
            $returned_at = null;
        }elseif ($status == CrowdFunding::COMPLETED) {
            $success_at = today()->subDay(rand(1,20))->addHours(rand(1,20))->addMinutes(rand(2,55));
            $returned_at = $success_at->addDays(rand(5,10));
        }
        return [
            //
            // 'user_id' => ,
            'paid_deposit'  => [0,1][rand(0,1)],
            'using_period'  => 90,
            'start_at'      => $success_at,
            'end_at'        => $success_at ? $success_at->addDays(90)->toDateString() : null,
            'returned_at'   => $returned_at,
            'status'        => $status,
            // 'comment'
        ];
    }
}
