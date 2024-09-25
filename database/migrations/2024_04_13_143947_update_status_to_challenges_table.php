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
        Schema::table('challenges', function (Blueprint $table) {
            //
            // $table->enum("status", array_keys(Challenge::statusOptions()))->change();
            \DB::statement("ALTER TABLE challenges MODIFY COLUMN status enum('applying','challenging','success','canceled', 'rejected');");
            $table->string('reason')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('challenges', function (Blueprint $table) {
            //
            $table->dropColumn('reason');
        });
    }
};
