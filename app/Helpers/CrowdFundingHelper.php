<?php
namespace App\Helpers;
use App\Models\User;
use App\Models\CrowdFunding;
use App\Wechat;
use DB;

class CrowdFundingHelper
{
    static public function makeSuccess($crowdFunding)
    {
        // $crowdFunding
    }

    static public function stats()
    {
        return [
            ['label' => __('Mutual Community People'),  'value' => CrowdFunding::count()],
            ['label' => __('Mutual Funding People'),    'value' => CrowdFunding::where('status', CrowdFunding::WAITING) ->count()],
            ['label' => __('Get Funding People'),       'value' => CrowdFunding::where('status', CrowdFunding::USING)   ->count()],
            ['label' => __('Return Funding People'),    'value' => CrowdFunding::where('status', CrowdFunding::COMPLETED)->count()]
        ];
    }
}
