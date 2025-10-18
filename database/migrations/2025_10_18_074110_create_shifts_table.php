<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration for shifts table
 * 
 * Stores work shift information (Morning, Afternoon, Night shifts)
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            
            // Shift identifiers
            $table->string('shift_id')->unique()->comment('Unique shift identifier');
            $table->string('shift_name')->comment('Shift name');
            
            // Shift timing
            $table->time('start_time')->nullable()->comment('Shift start time');
            $table->time('end_time')->nullable()->comment('Shift end time');
            
            // Status
            $table->boolean('status')->default(true)->comment('Active status');
            
            // Created by
            $table->foreignId('created_by_id')->nullable()->constrained('users')->nullOnDelete();
            
            // Timestamps
            $table->timestamps();
            
            // Indexes
            $table->index('status', 'idx_shifts_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};
