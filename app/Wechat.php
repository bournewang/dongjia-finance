<?php
namespace App;
use EasyWeChat\Pay\Application as PaymentApp;
use EasyWeChat\MiniApp\Application as MiniApp;
use EasyWeChat\MiniApp\Utils;

class Wechat
{
    static public function app()
    {
        return new MiniApp(config("wechat.mini_app"));
    }

    static public function codeToSession($code)
    {
        $app = self::app();
        $utils = new Utils($app);//$app->getUtils();
        return $utils->codeToSession($code);
    }

    static public function codeToPhoneNumber($code)
    {
        $app = self::app();
        return $app->getClient()->postJson('wxa/business/getuserphonenumber', ['code' => $code]);
    }

    static public function pay()
    {
        $app = new PaymentApp(config('wechat.payment'));
        $response = $app->getClient()->postJson("v3/pay/transactions/native", [
           "mchid" => config("wechat.payment.mch_id"),
           "out_trade_no" => "20240205".sprintf("%05d", rand(1,1000)),
           "appid" => config("wechat.mini_app.app_id"),
           "description" => "test order",
           "notify_url" => "https://weixin.qq.com/",
           "amount" => [
                "total" => 1,
                "currency" => "CNY"
            ],
            // "payer" => [
            //     "openid" => "o0fPH61V_9bagKhRKc6qLgqZcIAQ"
            // ]
        ]);

        \dd($response->toArray(false));
    }
}
