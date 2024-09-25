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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->enum("company_type", array_keys(Challenge::typeOptions()));
            $table->string('execute_partner')->nullable();
            $table->string('partner_role')->nullable();
            $table->string('company_name')->nullable();
            $table->string('credit_code')->nullable();
            $table->bigInteger('legal_person_id')->unsigned()->nullable();
            $table->date('registered_at')->nullable();
            $table->integer("partner_years")->nullable();
            $table->date('partner_start_at')->nullable();
            $table->date('partner_end_at')->nullable();

            $table->string('bank')->nullable();
            $table->string('sub_bank')->nullable();
            $table->string('account_name')->nullable();
            $table->string('account_no')->nullable();
            $table->timestamps();

            $table->foreign('legal_person_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
