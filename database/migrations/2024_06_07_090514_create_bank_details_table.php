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
        Schema::create('bank_details', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id');
            $table->string('account_holders_name');
            $table->string('bank_name');
            $table->string('bank_code');
            $table->string('bank_account_number');
            $table->string('branch_name');
            $table->string('branch_code');
            $table->string('linked_phone_number');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_details');
    }
};
