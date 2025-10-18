<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('button_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('display_order');
            $table->foreignId('process_id')->nullable()->constrained('processes')->nullOnDelete();
            $table->unsignedBigInteger('button_id')->nullable(); // No FK constraint yet - will add after buttons table created
            $table->boolean('status')->default(true);
            $table->foreignId('created_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            
            $table->index(['status', 'display_order']);
            $table->index('button_id'); // Add index for performance
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('button_groups');
    }
};
