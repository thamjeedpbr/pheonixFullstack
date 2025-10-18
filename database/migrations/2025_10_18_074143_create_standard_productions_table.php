<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('standard_productions', function (Blueprint $table) {
            $table->id();
            $table->string('range_from')->unique();
            $table->string('range_to');
            $table->string('name');
            $table->string('color');
            $table->boolean('coated')->default(false);
            $table->string('make_ready_time');
            $table->string('make_ready_sheet');
            $table->string('average_speed');
            $table->boolean('status')->default(true);
            $table->timestamps();
            
            $table->index('status');
        });

        Schema::create('standard_production_material', function (Blueprint $table) {
            $table->id();
            $table->foreignId('standard_production_id')->constrained('standard_productions')->cascadeOnDelete();
            $table->foreignId('material_id')->constrained('materials')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('standard_production_machine', function (Blueprint $table) {
            $table->id();
            $table->foreignId('standard_production_id')->constrained('standard_productions')->cascadeOnDelete();
            $table->foreignId('machine_id')->constrained('machines')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('standard_production_machine');
        Schema::dropIfExists('standard_production_material');
        Schema::dropIfExists('standard_productions');
    }
};
