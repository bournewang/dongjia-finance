<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         // $this->call([
         //     UserSeeder::class
         // ]);
        // echo "create admin with pass: ".env('ADMIN_PASS')."\n";
        User::create(['name' => 'Admin', 'email' => env('ADMIN_EMAIL'), 'password' => bcrypt(env('ADMIN_PASS'))]);
        User::factory(200)->create();
        // \App\Models\Challenge::factory(50)->create();
        \App\Models\Order::factory(10)->create();
        foreach (User::whereBetween('id', [6,10])->get() as $user) {
            $root = User::find(rand(1,5));
            $referer = $root; //User::find(rand(2,20));
            $user->update(['root_id' => $root->id, 'referer_id' => $referer->id]);
            \App\Models\Relation::create([
                'user_id' => $user->id,
                // 'root_id' => 1,
                'path' => ($referer->relation->path ?? ",").$referer->id.","
            ]);
            // if (rand(0,1)) {
            //     \App\Models\Challenge::factory()->create();
            // }
        }
        foreach (User::where('id', '>', 10)->get() as $user) {
            // $root = User::find(rand(1,5));
            $referer = User::find(rand(6,10));
            $user->update(['root_id' => $referer->root_id, 'referer_id' => $referer->id]);
            \App\Models\Relation::create([
                'user_id' => $user->id,
                // 'root_id' => 1,
                'path' => ($referer->relation->path ?? ",").$referer->id.","
            ]);
        }
        foreach (User::all() as $user){
            \App\Models\Challenge::factory()->create(['user_id' => $user->id]);
            if (rand(0,3) == 1){
                \App\Models\CrowdFunding::factory()->create(['user_id' => $user->id]);
            }
        }

        \App\Models\App::factory(10)->create();
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
