<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration for machine_types table
 * 
 * Stores different types of machines used in manufacturing
 * (PRE_PRESS, PRESS, POST_PRESS, DIE_CUT, OTHER)
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('machine_types', function (Blueprint $table) {
            $table->id();
            
            // Unique identifier
            $table->string('type_id')->unique()->comment('Unique type identifier');
            
            // Type information
            $table->string('name')->comment('Machine type name');
            $table->text('remark')->nullable()->comment('Additional remarks');
            
            // Machine type enum
            $table->enum('machine_type', ['PRE_PRESS', 'PRESS', 'POST_PRESS', 'DIE_CUT', 'OTHER'])
                  ->default('OTHER')
                  ->comment('Machine category');
            
            // Status
            $table->boolean('status')->default(true)->comment('Active status');
            
            // Timestamps
            $table->timestamps();
            
            // Indexes
            $table->index('status', 'idx_machine_types_status');
            $table->index('machine_type', 'idx_machine_types_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('machine_types');
    }
};
