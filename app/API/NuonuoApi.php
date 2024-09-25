<?php
namespace App\API;
use Buqiu\Invoice\InvoiceSDK;

class NuonuoApi {
    private $sdk;
    private $token;
    private $type;
    private $config;
    // usage: 
    // (new NuonuoApi('payment'))->preorder(...)
    // (new NuonuoApi('invoice'))->createInvoice(...)
    public function __construct($type = 'payment')
    {
        $this->type = $type;
        $config = config('invoice');
        if ($type === 'payment') {
            $config['app_key'] = env('NUONUO_APP_KEY');
            $config['app_secret'] = env('NUONUO_APP_SECRET');
            $this->token = env("NUONUO_TOKEN");
        }else {
            $config['app_key'] = env('NUONUO_INVOICE_APP_KEY');
            $config['app_secret'] = env('NUONUO_INVOICE_APP_SECRET');
            $this->token = env("NUONUO_INVOICE_TOKEN");
        }

        $this->config = $config;
        $this->sdk = new InvoiceSDK($config);
    }

    public function createInvoice($orderNo, $buyerName, $goodsName, $price, $taxRate, $num = 1)
    {
        return $this->request("nuonuo.OpeMplatform.requestBillingNew", [
            "buyerName" => $buyerName,
            "orderNo" => $orderNo,
            "invoiceDate" => now()->toDateTimeString(),
            "pushMode" => -1,
            // "email" => "",
            "invoiceType" => 1,
            "invoiceLine" => "pc",
            "goodsName" => $goodsName,
            "price" => $price,
            "num" => $num,
            "taxRate" => $taxRate,
            "withTaxFlag" => 1
        ]);
    }

    public function query($serialNo)
    {
        $res = $this->request("nuonuo.OpeMplatform.queryInvoiceResult", [
            "serialNos" => $serialNo,
            "isOfferInvoiceDetail" => 1
        ]);
        return $res->result[0];
    }

    public function preorder($order_no, $goodsName, $goodsNum, $amount, $openId)
    {
        $data = [
            "amount" => $amount, // String	Y	0.01		支付金额支付金额必须不大于999999999.99且小数位数为两位
            "subject" => $goodsName, // String	Y	测试支付	100	交易商品名称
            "goodsListItem" => [
                ["amount" => $amount, "goodsName" => $goodsName, "goodsNum" => $goodsNum]
            ], // Array	Y			开票商品列表
            "deptId" => "1", // String	Y		50	部门id
            "userid" => $openId, // String	Y	2088702292854236	100	用户标识
            // timeExpire	String	N	5		订单有效时间（必须是1-120之间的整数）
            "sellerNote" => $goodsName, // String	Y	测试	255	商家备注
            "payType" => "WECHAT", // String	Y	ALIPAY		支付类型（只能是WECHAT/ALIPAY）
            "appid" => env('WECHAT_MINIAPP_ID'), // String	Y	wxd51f8fef5c0fc8d1	36	小程序appid
            "notifyUrl" => env('APP_URL').'api/wxapp/notify', // String	Y	http://www.baidu.com	255	支付完成异步通知地址
            "appKey" => $this->config["app_key"], // String	Y	ASD125FAA	100	开放平台分配给应用的appKey
            "taxNo" => $this->config["tax_num"], // String	Y	339901999999142	50	商户税号
            "customerOrderNo" => $order_no, // String	Y	20221114092116250017	64	商户订单号
            // "openid" => "oWCUh7R2lH6kNoWMoIDm5GpMFrsg"
        ];
        $body = json_encode($data);
        $sendid = md5($body);

        return $this->sdk->sendPostSyncRequest($sendid, $this->token, "nuonuo.AggregatePay.miniprogramsquery", $body);

        // return $this->request("nuonuo.AggregatePay.miniprogramsquery", $data);
    }

    private function request($method, $data)
    {
        $body = $this->sdk->getBody($data, $method); // 获取过滤参数
        // echo "body: {$body}";
        $sendid = md5($body);

        return $this->sdk->sendPostSyncRequest($sendid, $this->token, $method, $body);
    }
}
