<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use \App\Models\Order;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $ids = \App\Models\User::pluck('id')->toArray();
        if(rand(0,1)) {
            $paid_at = today()->addHours(rand(8,20))->addMinutes(rand(0,60))->addSeconds(rand(0,60));
            $status = Order::PAID;
        }else{
            $paid_at = null;
            $status = Order::CREATED;
        }
        return [
            //
            'user_id' => $ids[rand(0,count($ids)-1)],
            'order_no' => today()->toDateString().sprintf("%08s", rand(1,5000)),
            'amount' => [500,1000,3000][rand(0, 2)],
            'paid_at' => $paid_at,
            'status' => $status,
            'created_at' => today()->addHours(rand(6,9))->addMinutes(rand(0,60))->addSeconds(rand(0,60))
        ];
    }
}
