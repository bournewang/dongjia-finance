<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Challenge;
use App\Models\CrowdFunding;
use App\Models\Company;
use App\Models\Banner;
use App\Models\App;
use App\Helpers\UserHelper;
use App\Helpers\ChallengeHelper;
use App\Helpers\FormHelper;
use App\Helpers\CarOwnerHelper;
use App\Helpers\CrowdFundingHelper;
use App\Wechat;

class PublicController extends ApiBaseController
{
    public function areaData(Request $req)
    {
         return $this->sendResponse(json_decode(file_get_contents(database_path("areadata.json"))));
    }

    public function privacy()
    {
        return $this->sendResponse(file_get_contents(database_path("privacy.txt")));
    }

    // combine index page data in one api
    public function index()
    {
        $img_url = url("/storage/mpp/");
        $data = [
            'challengeStats' => ChallengeHelper::stats(),
            'challengeLevels' => array_slice(config('challenge.levels'), 3),
            'carOwnerStats' => CarOwnerHelper::stats(),
            'fundingStats'  => CrowdFundingHelper::stats(),
            'fundingConfig' => config("car-manager.funding"),
            "images" => [
                "apply" => [
                    "car_manager"   => $img_url."/apply-car-manager-1.jpg",
                    "car_owner"     => $img_url."/apply-car-owner-1.jpg",
                    "consumer"      => $img_url."/apply-consumer-1.jpg",
                ],
                "partner" => [
                    "car_manager"   => $img_url."/partner-car-manager.jpg",
                    "car_owner"     => $img_url."/partner-car-owner.jpg",
                    "consumer"      => $img_url."/partner-consumer.jpg",
                ]
            ],
            "welcome" => config("challenge.welcome"),
            "notice" => ChallengeHelper::notice()
        ];
        return $this->sendResponse($data);
    }

    public function formOptions()
    {
        $data = [
            "partnerAssetFields"=> FormHelper::partnerAssetFields($this->user->challenge_type),
            "companyOptions"    => FormHelper::companyFields($this->user),
            "carViewFields"     => FormHelper::carViewFields(),
            "carFormFields"     => FormHelper::carFormFields(),
            "consumerFields"    => FormHelper::consumerFields(),
            "salesFields"       => FormHelper::salesFields(),
            "partnerStatsFields"=> FormHelper::partnerStatsFields()
        ];
        return $this->sendResponse($data);
    }

    public function apps()
    {
        $apps = \App\Models\App::where('status', 1)->get();
        $data = [];
        foreach ($apps as $app) {
            $data[] = $app->info();
        }
        return $this->sendResponse($data);
    }

    public function banners()
    {
        $apps = Banner::where('status', 1)->where("category", Banner::BANNER)->get();
        $data = [];
        foreach ($apps as $app) {
            $data[] = $app->info();
        }
        return $this->sendResponse($data);
    }

    public function ads()
    {
        $apps = Banner::where('status', 1)->where("category", Banner::AD)->get();
        $data = [];
        foreach ($apps as $app) {
            $data[] = $app->info();
        }
        return $this->sendResponse($data);
    }

    public function market()
    {
        $banners = Banner::where('status', 1)->orderBy("sort")->get();
        $data = [
            Banner::BANNER => [],
            Banner::AD => [],
            App::APP => [],
            App::TOOL => []
        ];
        foreach ($banners as $banner) {
            $data[$banner->category][] = $banner->info();
        }

        $apps = \App\Models\App::where('status', 1)->orderBy("sort")->get();
        foreach ($apps as $app) {
            $data[$app->category][] = $app->info();
        }

        return $this->sendResponse($data);
    }

    public function rules()
    {
        return $this->sendResponse(config("rules"));
    }
}
