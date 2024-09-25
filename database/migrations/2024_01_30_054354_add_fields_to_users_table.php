<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
// use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->bigInteger('root_id')->unsigned()->nullable();
            $table->bigInteger('referer_id')->unsigned()->nullable();
            $table->string('openid', 64)->nullable()->unique();
            $table->string('platform_openid', 64)->nullable()->unique();
            $table->string('nickname', 32)->nullable();
            $table->string('avatar')->nullable();
            $table->string('mobile', 24)->nullable()->unique();
            $table->string('qrcode', 64)->nullable();
            $table->string('id_no', 24)->nullable();
            $table->decimal('balance', 10, 2)->nullable();

            $table->string("province_code")->nullable();
            $table->string("province_name")->nullable();
            $table->string("city_code")->nullable();
            $table->string("city_name")->nullable();
            $table->string("county_code")->nullable();
            $table->string("county_name")->nullable();
            $table->string("street")->nullable();
            // $table->decimal('quota', 10, 2)->nullable();
            $table->integer('level')->default(0);

            // $table->string('wechat', 24)->nullable();
            // $table->string('bank_key', 24)->nullable();
            // $table->string('bank_name', 32)->nullable();
            // $table->string('account_no', 32)->nullable();
            $table->boolean('status')->default(1);
            $table->timestamp('certified_at')->nullable();
            // $table->enum('status', array_keys(User::statusOptions()))->nullable();
            // $table->string('api_token', 80)->nullable();
            $table->bigInteger('challenge_id')->unsigned()->nullable();
            $table->bigInteger('crowd_funding_id')->unsigned()->nullable();
            $table->softDeletes();

            $table->foreign('referer_id')->references('id')->on('users');
            $table->foreign('root_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
