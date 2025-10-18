<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('machines', function (Blueprint $table) {
            $table->id();
            $table->string('machine_id')->unique();
            $table->string('machine_name');
            $table->text('description')->nullable();
            $table->string('min_width')->nullable();
            $table->string('min_height')->nullable();
            $table->string('max_height')->nullable();
            $table->string('max_width')->nullable();
            $table->string('min_gsm')->nullable();
            $table->string('max_gsm')->nullable();
            $table->boolean('status')->default(true);
            $table->string('per_day_impression')->nullable();
            $table->string('per_hour_impression')->nullable();
            $table->text('remarks')->nullable();
            $table->string('make_ready_time')->nullable();
            $table->string('minimum_cost')->nullable();
            $table->string('per_hour_cost')->nullable();
            $table->string('meter_per_impression')->nullable();
            $table->string('devise_url')->nullable();
            $table->foreignId('machine_type_id')->nullable()->constrained('machine_types')->nullOnDelete();
            $table->foreignId('process_id')->nullable()->constrained('processes')->nullOnDelete();
            $table->foreignId('created_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            
            $table->index(['status', 'machine_type_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('machines');
    }
};
