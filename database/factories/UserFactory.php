<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use Carbon\Carbon;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $date = Carbon::today()->subDays(rand(0,30));
        return [
            // 'root_id' => rand(1,5),
            'openid' => str_replace(' ', '', fake()->unique()->text(32)),
            'platform_openid' => str_replace(' ', '', fake()->unique()->text(32)),
            'name' => fake()->name(),
            'nickname' => fake()->name(),
            'mobile' => fake()->phoneNumber(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'certified_at' => rand(0,1) ? $date->addDays(rand(0,3)) : null,
            'remember_token' => Str::random(10),
            'status' => rand(0,1),
            'level' => 0,//rand(1,5),
            'created_at' => $date
            // 'referer_id' => User::pluck('id')->toArray()[rand(0,User::count()-1)]
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
