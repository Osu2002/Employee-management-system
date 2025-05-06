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
        Schema::table('contact_h_r_messages', function (Blueprint $table) {
            $table->json('reply')->nullable(); // Change reply to JSON
            $table->json('emp_replies')->nullable()->after('reply'); // Add emp_replies
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('contact_h_r_messages', function (Blueprint $table) {
            $table->text('reply')->nullable(); // Revert reply to text
            $table->dropColumn('emp_replies'); // Drop emp_replies
        });
    }
};
