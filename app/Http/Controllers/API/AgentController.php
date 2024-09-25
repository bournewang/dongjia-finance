<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CrowdFunding;
use App\Helpers\UserHelper;
use App\Wechat;

class AgentController extends ApiBaseController
{
    public function managers(Request $request)
    {
        if (!$agent = $this->user->agent){
            return $this->sendError("no eligable");
        }
        $users = User::where('province_code',$agent->province_code)
            ->where('city_code',    $agent->city_code)
            ->where('county_code',  $agent->county_code)
            ->where('level', '>', User::CONSUMER_MERCHANT)
            ->get();
        $data = [];
        foreach ($users as $user) {
            $data[] = $user->info();
        }
        return $this->sendResponse($data);
    }

    public function manager($id)
    {
        if (!$user = User::find($id)) {
            return $this->sendError("no user with id $id");
        }
        $data = [
            'manager'       => $user->info(),
            'partnerStats'  => UserHelper::recommendStats($user)
        ];
        return $this->sendResponse($data);
    }
}
