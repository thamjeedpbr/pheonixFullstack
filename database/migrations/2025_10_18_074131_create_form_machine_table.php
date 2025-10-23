<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('form_machine', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_id')->constrained('forms')->cascadeOnDelete();
            $table->foreignId('machine_id')->constrained('machines')->cascadeOnDelete();
            
            // Tracking fields
            $table->boolean('is_active')->default(false)->comment('Currently selected machine');
            $table->timestamp('selected_at')->nullable()->comment('When this machine was selected');
            $table->timestamp('deselected_at')->nullable()->comment('When this machine was deselected');
            $table->integer('duration_minutes')->nullable()->comment('Total time this machine was used (minutes)');
            
            $table->timestamps();
            
            $table->unique(['form_id', 'machine_id']);
            $table->index(['form_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('form_machine');
    }
};
