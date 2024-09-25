<?php
namespace App\Helpers;

class AreaHelper
{
    // area: province_code|city_code|county_code
    // example: 360000|360100|360102
    static public function parse($area)
    {
        $areas = explode('|', $area);
        $input = [];
        $areaData = json_decode(file_get_contents(database_path("areadata.json")), 1);
        if ($code = $areas[0] ?? null) {
            $input['province_code'] = $code;
            $input['province_name'] = $areaData['provinces'][$code] ?? null;
        }
        if ($code = $areas[1] ?? null) {
            $input['city_code'] = $code;
            $input['city_name'] = $areaData['cities'][$code] ?? null;
        }
        if ($code = $areas[2] ?? null) {
            $input['county_code'] = $code;
            $input['county_name'] = $areaData['counties'][$code] ?? null;
        }
        return $input;
    }
}
