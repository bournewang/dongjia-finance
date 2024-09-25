<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Challenge;
use App\Models\User;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Challenge>
 */
class ChallengeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // $ids = User::pluck('id')->toArray();
        $types = array_keys(Challenge::typeOptions());
        return [
            //
            // 'user_id' => $ids[rand(0,count($ids)-1)],
            'index_no' => sprintf("%08s", rand(1,50)),
            'level' => rand(0, 4) ? 1 : 2,
            'type' => $types[rand(0, count($types)-1)],
            'success_at' => null, //now()->subDays(rand(0,7)),
            'status' => rand(0,5) ? Challenge::CHALLENGING : Challenge::APPLYING,// array_keys(Challenge::statusOptions())[rand(0,1)],
            'created_at' => today()->subDays(rand(0,30))
        ];
    }
}
