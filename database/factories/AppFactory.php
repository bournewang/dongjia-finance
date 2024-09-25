<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\App>
 */
class AppFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = array_keys(\App\Models\App::typeOptions());
        $type = $types[rand(0, count($types) - 1)];
        if ($type == 'web') {
            $url = "https://wx.yzsmjkkjcom.com/";
        }else{
            $url = "xxxxxxxx";
        }
        return [
            //
            "name" => fake()->name,
            "type" => $type,
            "url" => $url,
            "status" => 1
        ];
    }
}
