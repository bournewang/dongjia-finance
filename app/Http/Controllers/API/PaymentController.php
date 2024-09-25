<?php

namespace App\Http\Controllers\API;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Challenge;
use App\Models\User;
use App\Helpers\ChallengeHelper;
use App\Models\Company;
use App\Models\Order;
use App\API\NuonuoApi;
use Log;

class PaymentController extends ApiBaseController
{
    public function registerConsumer(Request $request)
    {
        $request->validate([
            // 'type' => 'required|string',
            // 'goodsName' => 'required|string',
            // 'goodsNum' => 'required|integer',
            // 'amount' => 'required|numeric',
        ]);
        $input = $request->all();
        $index = $this->countOrdersToday();
        $order_no = date("Ymd").sprintf("%05d", $index);
        
        $order = Order::create([
            'user_id' => $this->user->id,
            'order_no' => $order_no,
            'amount' => 0.01,
            'status' => Order::CREATED,
            'type' => 'register-consumer',
            'paid_at' => null,
            'refund_at' => null
        ]);
        $res = (new NuonuoApi('payment'))->preorder($order->order_no, 'Register Consumer', 1, $order->amount, $this->user->openid);
        if ($res->code == 'JH200' && $res->result->payInfo) {
            return $this->sendResponse(json_decode($res->result->payInfo));
        }else{
            return $this->sendError($res->describe ?? "something wrong");
        }
    }

    private function countOrdersToday()
    {
        $today = Carbon::today();
        $count = Order::whereDate('created_at', $today)->count(); // This works with datetime format
        return $count;
    }

}