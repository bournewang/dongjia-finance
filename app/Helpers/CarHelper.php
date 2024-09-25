<?php

namespace App\Helpers;
use App\Models\CarModel;
use App\API\VinApi;

class CarHelper
{
    static public function vinToModel($vin)
    {
        if (!$model = CarModel::firstWhere("vin", $vin)){
            $res = VinApi::get($vin);
            $data = [];
            foreach($res as $key => $val){
                if (is_array($val))
                    $val =json_encode($val);
                $data[$key] = $val;
            }
            $model = CarModel::create($data);
        }

        return $model;
    }
}
