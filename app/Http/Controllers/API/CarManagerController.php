<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CrowdFunding;
use App\Helpers\UserHelper;
use App\Helpers\CrowdFundingHelper;
use App\Wechat;

class CarManagerController extends ApiBaseController
{
    public function fundingStas(Request $req)
    {
        $data = [
                'stats' => CrowdFundingHelper::stats()
        ];
        if ($req->input('activity', false)) {
            $list = CrowdFunding::orderBy('id', 'desc')->limit(20)->get();
            // $statusOptions = CrowdFunding::statusOptions();
            $activity = [];
            $display = [
                CrowdFunding::APPLYING  => "正在申请互助金",
                CrowdFunding::WAITING   => "排队等候中",
                CrowdFunding::USING     => "正在使用互助金",
                CrowdFunding::COMPLETED => "已归还互助金",
                CrowdFunding::CANCELED  => "已取消使用互助金",
            ];
            foreach ($list as $item){
                // $data[] = "$item->success_at ".$item->user->name ."挑战".$item->user->levelLabel()."成功！";
                $activity[] = [
                    "updated_at" => $item->updated_at ? $item->updated_at->toDateTimeString() : null,
                    "content" => $item->user->displayName() . $display[$item->status]."!",
                    "avatar" => $item->user->avatar
                ];
            }
            $data['activity'] =$activity;
        }
        return $this->sendResponse($data);
    }

    public function fundingConfig()
    {
        return $this->sendResponse(config("car-manager.funding"));
    }

}
