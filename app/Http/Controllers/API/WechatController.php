<?php

namespace App\Http\Controllers\API;
use Carbon\Carbon;
use Illuminate\Http\Request;
// use App\Models\Store;
use App\Models\User;
use App\Models\Order;
use App\Models\Relation;
use App\Helpers\UserHelper;
use App\Wechat;
use Log;

class WechatController extends ApiBaseController
{
    public function login(Request $request)
    {
        debug(__CLASS__.'->'.__FUNCTION__);
        debug($request->all());
        if (!$code = $request->input('code')) {
            throw new \Exception("no code");
        }
        // $mpp = \EasyWeChat::miniProgram();
        // $data = $mpp->auth->session($code);
        $data = Wechat::codeToSession($code);
        debug($data);
        \Cache::put("wx.session.".$data['session_key'], json_encode($data), 60*5);
        if (!$openid = $data['openid'] ?? null) {
            return $this->sendError('no openid', [
                'session_key' => $data['session_key']
            ]);
        }
        $referer = null;
        $referer_id = $request->input('referer_id', null);
        if ($referer_id && $referer = User::find($referer_id)) {
            debug("referer {$referer->id}: " . json_encode([
                'leve' => $referer->level,
                'challenge_type' => $referer->challenge_type,
                // 'challenge_type_label' => $referer->challenge_type_label,
            ]));
        }
        if ($user = User::firstWhere('openid', $openid)) {
            debug("user {$user->id} found with openid: $openid");

            // if user's previous referer is not a challenger,
            // user can be re-assign to new referer
            if ($user->referer){
                debug("previous referer {$user->referer_id} : " . json_encode([
                    'leve' => $user->referer->level,
                    'challenge_type' => $user->referer->challenge_type,
                    // 'challenge_type_label' => $user->referer->challenge_type_label,
                ]));
            }
            if (
                $referer
                && $referer->id != $user->referer_id                        // referer changes
                && ($user->referer->level ?? 0) < User::CONSUMER_MERCHANT   // not a challenger
                && ($user->referer->level ?? 0) < $referer->level           // new referer's level greater than previous referer's level
            ) {
                debug("referer changes AND referer level greater than previous, update");
                $user->update([
                    'referer_id'            => $referer->id,
                    'challenge_type'        => $referer->challenge_type ?? null,
                    'challenge_type_label'  => $referer->challenge_type_label ?? null,
                ]);
                if ($user->relation) {
                    $user->relation->update(['path' => $referer->path.",".$referer->id]);
                }else{
                    Relation::create([
                        'root_id' => $referer->root_id ?? null,
                        'user_id' => $user->id,
                        'path' => $referer->path.",".$referer->id
                    ]);
                }
            }
        }else{
            debug("user not found with openid: $openid");
            $mobile = $request->input('mobile', null);
            $info = [
                // 'store_id'  => $store_id,
                'openid'    => $openid,
                'mobile'    => $mobile,
                'name'      => $request->input('name', null),
                'nickname'  => $request->input('nickname', null),
                'avatar'    => $request->input('avatar', null),
                'email'     => $openid."@xiaofeice.com",
                'password'  => bcrypt($mobile || $openid),
                'referer_id'=> $referer_id,
                'challenge_type'        => $referer->challenge_type ?? null,
                'challenge_type_label'  => $referer->challenge_type_label ?? null,
                'level'     => 0
            ];

            if ($mobile && $user = User::firstWhere('mobile', $mobile)) {
                debug("get user with mobile, update user ".$user->id);
                debug($info);
                $user->update($info);
            }else{
                debug("try to create user: " . json_encode($info));
                $user = User::create($info);
                if ($referer) {
                    Relation::create([
                        'root_id' => $referer->root_id ?? null,
                        'user_id' => $user->id,
                        'path' => $referer->path.",".$referer->id
                    ]);
                }
            }
            UserHelper::createQrCode($user);
        }

        $info = $user->info();
        $info['api_token'] = $user->createToken("api")->plainTextToken;
        return $this->sendResponse($info);
    }

    public function register(Request $request)
    {
        debug(__CLASS__.'->'.__FUNCTION__);
        debug($request->all());
        if (!$session_key = $request->input('session_key')) {
            // throw new ApiException("no code");
        }
        // $mpp = EasyWeChat::miniProgram();
        // $data = $mpp->phone_number->getUserPhoneNumber($request->input('code'));
        $data = Wechat::codeToPhoneNumber($request->input('code'));
        debug($data);
        if (!isset($data['errcode']) || $data['errcode'] != 0) {
            return $this->sendError("fetch phone number failed: ".$data['errmsg']);
        }
        $phone_number = ($data['phone_info']['purePhoneNumber'] ?? $data['phone_info']['phoneNumber']) ?? null;
        if (!$phone_number || strlen($phone_number) < 10) {
            return $this->sendError("请授权手机号码！");
        }

        if (!$string = \Cache::get("wx.session.".$session_key)) {
            return $this->sendError("no session found with key: $session_key");
        }
        $session = json_decode($string, 1);
        if (!$openid = ($session['openid'] ?? null)) {
            return $this->sendError("no openid in session data");
        }

        $store_id = intval($request->input('store_id', null));
        $store_id = $store_id > 0 ? $store_id : null;
        // $setting = Setting::first();
        $info = [
            // 'store_id'  => $store_id,
            'openid'    => $openid,
            'mobile'    => $phone_number,
            'email'     => $openid."@wechat.com",
            'password'  => bcrypt($openid),
            // 'rewards_expires_at' => Carbon::today()->addDays($setting->level_0_rewards_days),
            'level'     => 0
        ];
        $referer_id = $request->input('referer_id', null);
        if (!$user = User::withTrashed()->firstWhere('mobile', $phone_number)) {
            debug("try to create user: " . json_encode($info));
            $info['referer_id'] = $referer_id;
            $user = User::create($info);
        }else{
            if ($user->deleted_at) {
                debug("restore user $user->id");
                $user->restore();
            }
            debug("update user $user->id: ".json_encode($info));
            $user->update($info);
        }

        if ($referer = User::find($referer_id)) {
            Relation::create([
                'root_id' => $referer->root_id,
                'user_id' => $user->id,
                'path' => $referer->path.",".$referer->id
            ]);
        }

        \Auth::login($user);
        debug("user: $user->id");
        $data = $user->info();
        $data['api_token'] = $user->createToken("api")->plainTextToken;
        return $this->sendResponse($data);
    }

    public function notify(Request $request)
    {
        debug(__CLASS__.'->'.__FUNCTION__);
        $app = \EasyWeChat::payment();
        //  data:
        //  array (
        //   'appid' => 'wx561877352e872072',
        //   'bank_type' => 'OTHERS',
        //   'cash_fee' => '1',
        //   'fee_type' => 'CNY',
        //   'is_subscribe' => 'N',
        //   'mch_id' => '1484920352',
        //   'nonce_str' => '61d592e64368c',
        //   'openid' => 'oZO6h5ft4olVbJcLfU4OEkBqYdxc',
        //   'out_trade_no' => '891840',
        //   'result_code' => 'SUCCESS',
        //   'return_code' => 'SUCCESS',
        //   'sign' => '573C1A93A6AE80BA2B743A5BBA0D7639',
        //   'time_end' => '20220105204530',
        //   'total_fee' => '1',
        //   'trade_type' => 'JSAPI',
        //   'transaction_id' => '4200001310202201054219704874',
        // )
        $response = $app->handlePaidNotify(function ($data, $fail) {
            debug($data);
            if ($data['result_code'] == 'SUCCESS' &&
                $data['return_code'] == 'SUCCESS' &&
                ($order_no = $data['out_trade_no'])) {
                if ($order = Order::where('order_no', $order_no)->first()) {
                    $order->update(['status' => Order::PAID, 'paid_at' => Carbon::now()]);
                    // OrderHelper::profitSplit($order);
                    return true;
                }
            }
            // 或者错误消息
            $fail('Something going wrong.');
        });
        $response->send();
    }

    public function withdrawNotify(Request $request)
    {
        debug(__CLASS__.'->'.__FUNCTION__);
        debug($request->all());


    }
}
