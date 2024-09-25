<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AppSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // "type",
        // "category",
        // "ad_position",
        // "height",
        // "url",
        // "sort",
        // "status"
        $apps = [
            ["name" => "东家通路", "sort" => 1, "type" => "mpp", "category" => "app", "icon" => "tonglu.jpg", "url" => "wx330488813101da7a", "status" => 1],
            ["name" => "一车一碳", "sort" => 2, "type" => "mpp", "category" => "app", "icon" => "onecar.jpg", "url" => "wx330488813101da7a", "status" => 1],
            ["name" => "华兴益购", "sort" => 3, "type" => "web", "category" => "app", "icon" => "huaxing.jpg", "url" => "https://wx.yzsmjkkjcom.com/", "status" => 1],

            ["name" => "数字人民币","sort" => 1, "type" => "mpp", "category" => "tool", "icon" => "rmb.png", "status" => 1],
            ["name" => "邮储银行",  "sort" => 2, "type" => "mpp", "category" => "tool", "icon" => "youchu.png", "status" => 1],
            ["name" => "企业微信",  "sort" => 3, "type" => "mpp", "category" => "tool", "icon" => "wecom.png", "status" => 1],
            ["name" => "腾讯会议",  "sort" => 4, "type" => "mpp", "category" => "tool", "icon" => "t-meeting.png", "url" => "wx33fd6cdc62520063", "status" => 1],
            ["name" => "钉钉",      "sort" => 5, "type" => "mpp", "category" => "tool", "icon" => "ding.png", "status" => 1],
            ["name" => "爱信诺",    "sort" => 6, "type" => "mpp", "category" => "tool", "icon" => "xinnuo.png", "status" => 1],
            ["name" => "诺言",     "sort" => 7, "type" => "mpp", "category" => "tool", "icon" => "nuoyan.png", "status" => 1],
            ["name" => "国家政务",  "sort" => 8, "type" => "mpp", "category" => "tool", "icon" => "zhengwu.png", "url" => "wx2eec5fb00157a603", "status" => 1],
        ];
        foreach ($apps as $data) {
            $icon = $data['icon'] ?? null;
            unset($data['icon']);
            $app = \App\Models\App::create($data);
            if ($icon ) {
                $path = "./tmp/".$icon;
                if (file_exists($path)) {
                    $app->addMedia($path)->preservingOriginal()->toMediaCollection("icon");
                }
            }
            echo "create app $app->name\n";
        }
    }
}
