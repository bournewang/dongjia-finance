<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('car_models', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("carid");
            $table->string("vin");
            $table->string("name");
            $table->string("brand")->nullable();
            $table->string("typename")->nullable();
            $table->string("logo")->nullable();
            $table->string("manufacturer")->nullable();
            $table->string("yeartype")->nullable();
            $table->string("environmentalstandards")->nullable();
            $table->string("comfuelconsumption")->nullable();
            $table->string("engine")->nullable();
            $table->string("fueltype")->nullable();
            $table->string("gearbox")->nullable();
            $table->string("drivemode")->nullable();
            $table->string("fronttiresize")->nullable();
            $table->string("reartiresize")->nullable();
            $table->string("displacement")->nullable();
            $table->string("displacementml")->nullable();
            $table->string("fuelgrade")->nullable();
            $table->string("price")->nullable();
            $table->string("chassis")->nullable();
            $table->string("frontbraketype")->nullable();
            $table->string("rearbraketype")->nullable();
            $table->string("parkingbraketype")->nullable();
            $table->string("maxpower")->nullable();
            $table->string("sizetype")->nullable();
            $table->string("gearnum")->nullable();
            $table->string("geartype")->nullable();
            $table->string("seatnum")->nullable();
            $table->string("bodystructure")->nullable();
            $table->string("maxhorsepower")->nullable();
            $table->string("iscorrect")->nullable();
            $table->json("machineoil")->nullable();
            $table->json("gearboxinfo")->nullable();
            $table->string("listdate")->nullable();
            $table->string("marketprice")->nullable();
            $table->string("version")->nullable();
            $table->string("groupid")->nullable();
            $table->string("groupname")->nullable();
            $table->string("isimport")->nullable();
            $table->string("doornum")->nullable();
            $table->string("len")->nullable();
            $table->string("width")->nullable();
            $table->string("height")->nullable();
            $table->string("wheelbase")->nullable();
            $table->string("weight")->nullable();
            $table->string("ratedloadweight")->nullable();
            $table->string("bodytype")->nullable();
            $table->string("enginemodel")->nullable();
            $table->string("cylindernum")->nullable();
            $table->string("fuelmethod")->nullable();
            $table->json("carlist")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_models');
    }
};
