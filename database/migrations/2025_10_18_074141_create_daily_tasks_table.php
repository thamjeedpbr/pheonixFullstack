<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->foreignId('machine_id')->nullable()->constrained('machines')->nullOnDelete();
            $table->foreignId('shift_id')->nullable()->constrained('shifts')->nullOnDelete();
            $table->boolean('status')->default(true);
            $table->boolean('clean_ink')->default(false);
            $table->boolean('clean_cylinder')->default(false);
            $table->boolean('clean_all_impression')->default(false);
            $table->boolean('clean_technotrans')->default(false);
            $table->boolean('clean_all_air_filters')->default(false);
            $table->boolean('check_water_pressure')->default(false);
            $table->boolean('check_grease_point')->default(false);
            $table->timestamps();
            
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_tasks');
    }
};
