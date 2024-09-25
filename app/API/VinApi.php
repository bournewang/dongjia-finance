<?php
namespace App\API;
use App\Request;

class VinApi
{
    static public function get($vin)
    {
        $request = new Request(
            'https://jisuvindm.market.alicloudapi.com',
            ['Authorization' => "APPCODE " . env("VIN_APPCODE")]
        );

        $res = $request->get("/vin/query", ["vin" => $vin]);
        if (($res['status'] ?? null) === 0) {
            return $res['result'] ?? null;
        }else{
            throw new \Exception($res['msg'] ?? null);
        }
    }
}
