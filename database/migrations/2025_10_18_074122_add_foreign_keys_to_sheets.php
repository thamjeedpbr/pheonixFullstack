<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Add foreign key constraints after all parent tables are created
     */
    public function up(): void
    {
        // Add foreign key for sheets.machine_id
        Schema::table('sheets', function (Blueprint $table) {
            $table->foreign('machine_id')
                  ->references('id')
                  ->on('machines')
                  ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sheets', function (Blueprint $table) {
            $table->dropForeign(['machine_id']);
        });
    }
};
