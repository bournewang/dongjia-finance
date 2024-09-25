<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\App;
class MigrateAppIdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        foreach (App::where('type', App::MPP)->get() as $app){
            echo "update {$app->name} set appid = {$app->url}, url = null\n";
            $app->update(['appid' => $app->url, 'url' => null]);

        }
    }
}
