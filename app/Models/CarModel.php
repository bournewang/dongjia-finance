<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarModel extends Model
{
    use HasFactory;

    protected $fillable = [
        "carid",
        "vin",
        "name",
        "brand",
        "typename",
        "logo",
        "manufacturer",
        "yeartype",
        "environmentalstandards",
        "comfuelconsumption",
        "engine",
        "fueltype",
        "gearbox",
        "drivemode",
        "fronttiresize",
        "reartiresize",
        "displacement",
        "displacementml",
        "fuelgrade",
        "price",
        "chassis",
        "frontbraketype",
        "rearbraketype",
        "parkingbraketype",
        "maxpower",
        "sizetype",
        "gearnum",
        "geartype",
        "seatnum",
        "bodystructure",
        "maxhorsepower",
        "iscorrect",
        "machineoil",
        "gearboxinfo",
        "listdate",
        "marketprice",
        "version",
        "groupid",
        "groupname",
        "isimport",
        "doornum",
        "len",
        "width",
        "height",
        "wheelbase",
        "weight",
        "ratedloadweight",
        "bodytype",
        "enginemodel",
        "cylindernum",
        "fuelmethod",
        "carlist",
    ];

}
