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
        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->string("province_code")->nullable();
            $table->string("province_name")->nullable();
            $table->string("city_code")->nullable();
            $table->string("city_name")->nullable();
            $table->string("county_code")->nullable();
            $table->string("county_name")->nullable();
            $table->enum("status", array_keys(App\Models\Agent::statusOptions()))->default(App\Models\Agent::APPLYING);
            $table->string("comment")->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agents');
    }
};
