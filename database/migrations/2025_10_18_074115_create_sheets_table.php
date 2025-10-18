<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sheets', function (Blueprint $table) {
            $table->id();
            $table->string('sheet_id')->unique();
            $table->string('sheet_size');
            $table->boolean('status')->default(true);
            $table->unsignedBigInteger('machine_id')->nullable(); // No FK yet
            $table->foreignId('created_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            
            $table->index('status');
            $table->index('machine_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sheets');
    }
};
