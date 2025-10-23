<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('form_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_id')->constrained('forms')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            
            // Tracking fields
            $table->boolean('is_working')->default(false)->comment('Currently working on this form');
            $table->timestamp('worked_at')->nullable()->comment('When this user started working');
            $table->timestamp('finished_at')->nullable()->comment('When this user finished working');
            $table->integer('duration_minutes')->nullable()->comment('Total time this user worked (minutes)');
            $table->text('notes')->nullable()->comment('User notes or remarks');
            
            $table->timestamps();
            
            $table->unique(['form_id', 'user_id']);
            $table->index(['form_id', 'is_working']);
            $table->index(['user_id', 'worked_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('form_user');
    }
};
