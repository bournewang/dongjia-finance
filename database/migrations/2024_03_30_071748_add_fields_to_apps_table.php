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
        Schema::table('apps', function (Blueprint $table) {
            //
            $table->enum("category", array_keys(\App\Models\App::categoryOptions()))->nullable();
            $table->string('url')->nullable()->change();
            $table->integer("sort")->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('apps', function (Blueprint $table) {
            //
            $table->dropColumn("category");
            $table->dropColumn("sort");
        });
    }
};
