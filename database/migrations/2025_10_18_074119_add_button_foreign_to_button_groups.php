<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Add foreign key constraint for button_groups.button_id after buttons table exists
     */
    public function up(): void
    {
        Schema::table('button_groups', function (Blueprint $table) {
            $table->foreign('button_id')
                  ->references('id')
                  ->on('buttons')
                  ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('button_groups', function (Blueprint $table) {
            $table->dropForeign(['button_id']);
        });
    }
};
