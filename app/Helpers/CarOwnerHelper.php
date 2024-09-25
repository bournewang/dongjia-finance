<?php
namespace App\Helpers;
use App\Models\User;
use App\Models\CrowdFunding;
use App\Wechat;
use DB;

class CarOwnerHelper
{
    static public function stats()
    {
        return [
            ['label' => __("Develop General Manager"),  'value' => 125],
            ['label' => __("General Manager Team"),     'value' => 100],
            ['label' => __("Car Owner"),                'value' => 50],
            ['label' => __("CCER Carbon Reduce Vehicle"), 'value' => 12],
        ];
    }
}
