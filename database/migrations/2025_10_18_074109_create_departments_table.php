<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration for departments table
 * 
 * Stores department information for material categorization
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            
            // Department identifiers
            $table->string('department_code')->unique()->comment('Unique department code');
            $table->string('name')->comment('Department name');
            $table->text('remark')->nullable()->comment('Additional remarks');
            
            // Status
            $table->boolean('status')->default(true)->comment('Active status');
            
            // Timestamps
            $table->timestamps();
            
            // Indexes
            $table->index('status', 'idx_departments_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
