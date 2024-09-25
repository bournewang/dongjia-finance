<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\CrowdFunding;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('crowd_fundings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->string('partner_role')->nullable();
            $table->boolean('paid_deposit')->default(false);
            $table->integer('using_period')->nullable();
            $table->date('start_at')->nullable();
            $table->date('end_at')->nullable();
            $table->date('returned_at')->nullable();
            $table->enum("status", array_keys(CrowdFunding::statusOptions()))->default(CrowdFunding::APPLYING);
            $table->string('comment')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crowd_fundings');
    }
};
