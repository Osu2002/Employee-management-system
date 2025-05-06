<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('job_roles', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->string('name'); // Job role name
            $table->text('description')->nullable(); // Description for the role
            $table->integer('allowed_leaves')->default(0); // Total allowed leaves
            $table->boolean('status')->default(1); // Status: 1 for Active, 0 for Inactive
            $table->timestamps(); // Created_at and updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('job_roles');
    }
};
