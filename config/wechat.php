<?php

return [

    'mini_app' => [
        'app_id' => env('WECHAT_MINIAPP_ID'),
        'secret' => env('WECHAT_MINIAPP_SECRET'),
        'token' => null,
        'aes_key' => env("WECHAT_MINIAPP_AESKEY")
    ],
    'payment' =>  [
        'mch_id' => env('WECHAT_PAYMENT_MCH_ID', 'your-mch-id'),

        // 商户证书
        'private_key' => env('WECHAT_PAYMENT_KEY_PATH', 'path/to/cert/apiclient_key.pem'),
        'certificate' => env('WECHAT_PAYMENT_CERT_PATH', 'path/to/cert/apiclient_cert.pem'),

         // v3 API 秘钥
        'secret_key' => env("WECHAT_PAYMENT_V3_KEY"),

        // v2 API 秘钥
        'v2_secret_key' => env("WECHAT_PAYMENT_V2_KEY"),

        // 平台证书：微信支付 APIv3 平台证书，需要使用工具下载
        // 下载工具：https://github.com/wechatpay-apiv3/CertificateDownloader
        'platform_certs' => [
            // 请使用绝对路径
            env("WECHAT_PLATFORM_CERT")
        ],

        /**
         * 接口请求相关配置，超时时间等，具体可用参数请参考：
         * https://github.com/symfony/symfony/blob/5.3/src/Symfony/Contracts/HttpClient/HttpClientInterface.php
         */
        'http' => [
            'throw'  => true, // 状态码非 200、300 时是否抛出异常，默认为开启
            'timeout' => 5.0,
            // 'base_uri' => 'https://api.mch.weixin.qq.com/', // 如果你在国外想要覆盖默认的 url 的时候才使用，根据不同的模块配置不同的 uri
        ],
    ]
];
