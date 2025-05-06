<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('user_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id'); // Foreign key to the employees table
            $table->string('cover_photo')->nullable(); // Path to the cover photo
            $table->string('project_name')->nullable(); // Name of the project
            $table->text('project_description')->nullable(); // Description of the project
            $table->json('skills')->nullable(); // JSON column for skills
            $table->json('contributors')->nullable(); // JSON column for contributors
            $table->json('media')->nullable(); // JSON column for media files
            $table->boolean('status')->default(0); // Status of the entry (active/inactive)
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('user_data');
    }
};
