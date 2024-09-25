<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $apps = [
            ["type" => "inner_url", "category" => "banner",  "image" => "banner-1.jpg", "url" => "/pages/rules/page?type=consumer", "sort" => 1, "status" => 1],
            ["type" => "inner_url", "category" => "banner",  "image" => "banner-2.jpg", "url" => "/pages/rules/page?type=car-manager", "sort" => 2, "status" => 1],
            ["type" => "inner_url", "category" => "banner",  "image" => "banner-3.jpg", "url" => "/pages/rules/page?type=car-owner", "sort" => 3, "status" => 1],
            ["type" => "inner_url", "category" => "banner",  "image" => "banner-4.jpg", "url" => null, "sort" => 4, "status" => 1],

            ["type" => "inner_url", "category" => "ad", "image" => "ad-1.png", "ad_position" => 1, "height" => "8em", "url" => "/pages/projects/page", "sort" => 1, "status" => 1],
            ["type" => "inner_url", "category" => "ad", "image" => "ad-2.jpg", "ad_position" => 2, "height" => "5em", "url" => "/pages/apply/page?type=agent", "sort" => 2, "status" => 1],

        ];
        foreach ($apps as $data) {
            $icon = $data['image'] ?? null;
            unset($data['image']);
            $app = \App\Models\Banner::create($data);
            if ($icon ) {
                $path = "./tmp/".$icon;
                if (file_exists($path)) {
                    echo "$path exists\n";
                    $app->addMedia($path)->preservingOriginal()->toMediaCollection("image");
                }
            }
            echo "create banner $app->name\n";
        }
    }
}
