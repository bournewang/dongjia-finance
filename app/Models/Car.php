<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "car_model_id",
        "plate_no",
        "vin"
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function carModel()
    {
        return $this->belongsTo(CarModel::class);
    }

    public function info()
    {
        $data = $this->toArray();
        $model_keys = ["brand", "name", "yeartype", "fuelgrade"];
        if ($model = $this->carModel) {
            foreach ($model->toArray() as $key => $val) {
                if (!in_array($key, $model_keys)) continue;
                $data['car_model_'.$key] = $val;
            }
        }
        return $data;
    }
}
