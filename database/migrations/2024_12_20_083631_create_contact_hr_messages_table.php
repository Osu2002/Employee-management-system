<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactHrMessagesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('contact_h_r_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Sender's name
            $table->string('email'); // Sender's email
            $table->string('subject'); // Message subject
            $table->text('message'); // Message content
            $table->timestamps(); // Created and updated timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_hr_messages');
    }
}
