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
        Schema::table('banners', function (Blueprint $table) {
            //
            $table->enum("category", array_keys(\App\Models\Banner::categoryOptions()))->nullable();
            $table->integer("ad_position")->nullable();
            $table->string("height")->nullable();
            $table->integer("sort")->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            //
            $table->dropColumn("category");
            $table->dropColumn("ad_position");
            $table->dropColumn("height");
            $table->dropColumn("sort");
        });
    }
};
