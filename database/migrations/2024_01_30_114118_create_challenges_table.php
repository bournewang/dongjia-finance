<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Challenge;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('challenges', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->string('index_no')->nullable();
            $table->string('partner_role')->nullable();
            // $table->
            $table->enum('type', array_keys(Challenge::typeOptions()));
            $table->tinyInteger('level')->default(0);
            $table->datetime('success_at')->nullable();
            $table->enum("status", array_keys(Challenge::statusOptions()))->default(Challenge::APPLYING);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('challenges');
    }
};
