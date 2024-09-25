<?php

namespace App;
use WeChatPay\Builder;
use WeChatPay\Crypto\Rsa;
use WeChatPay\Util\PemUtil;

class WePay{
    static public function (){

        // 'sandbox'            => env('WECHAT_PAYMENT_SANDBOX', false),
        // 'app_id'             => env('WECHAT_PAYMENT_APPID', ''),
        // 'mch_id'             => env('WECHAT_PAYMENT_MCH_ID', 'your-mch-id'),
        // 'key'                => env('WECHAT_PAYMENT_KEY', 'key-for-signature'),
        // 'cert_path'          => env('WECHAT_PAYMENT_CERT_PATH', 'path/to/cert/apiclient_cert.pem'),    // XXX: 绝对路径！！！！
        // 'key_path'           => env('WECHAT_PAYMENT_KEY_PATH', 'path/to/cert/apiclient_key.pem'),      // XXX: 绝对路径！！！！
        // 'notify_url'         => env('APP_URL').'/api/wechat/notify',                           // 默认支付结果通知地址
        // 商户号
        // $merchantId = config('wechat.payment.default.mch_id');

        // 从本地文件中加载「商户API私钥」，「商户API私钥」会用来生成请求的签名
        $merchantPrivateKeyFilePath = config('wechat.payment.default.key_path');
        $merchantPrivateKeyInstance = Rsa::from($merchantPrivateKeyFilePath, Rsa::KEY_TYPE_PRIVATE);

        // 「商户API证书」的「证书序列号」
        // $merchantCertificateSerial = config('wechat.payment.default.cert_serial');

        // 从本地文件中加载「微信支付平台证书」，用来验证微信支付应答的签名
        $platformCertificateFilePath = config('wechat.payment.default.platform_cert');
        $platformPublicKeyInstance = Rsa::from($platformCertificateFilePath, Rsa::KEY_TYPE_PUBLIC);

        // 从「微信支付平台证书」中获取「证书序列号」
        $platformCertificateSerial = PemUtil::parseCertificateSerialNo($platformCertificateFilePath);

        // 构造一个 APIv3 客户端实例
        $instance = Builder::factory([
            'mchid'      => config('wechat.payment.default.mch_id'),
            'serial'     => config('wechat.payment.default.cert_serial'),
            'privateKey' => $merchantPrivateKeyInstance,
            'certs'      => [
                $platformCertificateSerial => $platformPublicKeyInstance,
            ],
        ]);

        // 发送请求
        $resp = $instance->chain('v3/certificates')->get(
            ['debug' => true] // 调试模式，https://docs.guzzlephp.org/en/stable/request-options.html#debug
        );
        echo $resp->getBody(), PHP_EOL;

        try {
            $resp = $instance
            ->chain('v3/pay/transactions/native')
            ->post(['json' => [
                'mchid'        => config('wechat.payment.default.mch_id'),
                'out_trade_no' => 'native12177525012014070332333',
                'appid'        => config('wechat.payment.default.app_id'),,
                'description'  => 'Image形象店-深圳腾大-QQ公仔',
                'notify_url'   => 'https://weixin.qq.com/',
                'amount'       => [
                    'total'    => 1,
                    'currency' => 'CNY'
                ],
            ]]);

            echo $resp->getStatusCode(), PHP_EOL;
            echo $resp->getBody(), PHP_EOL;
        } catch (\Exception $e) {
            // 进行错误处理
            echo $e->getMessage(), PHP_EOL;
            if ($e instanceof \GuzzleHttp\Exception\RequestException && $e->hasResponse()) {
                $r = $e->getResponse();
                echo $r->getStatusCode() . ' ' . $r->getReasonPhrase(), PHP_EOL;
                echo $r->getBody(), PHP_EOL, PHP_EOL, PHP_EOL;
            }
            echo $e->getTraceAsString(), PHP_EOL;
        }
    }
}
