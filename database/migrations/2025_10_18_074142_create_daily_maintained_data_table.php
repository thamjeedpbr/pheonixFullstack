<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_maintained_data', function (Blueprint $table) {
            $table->id();
            $table->boolean('clean_ink')->nullable();
            $table->boolean('clean_cylinder')->nullable();
            $table->boolean('clean_all_impression')->nullable();
            $table->boolean('clean_technotrans')->nullable();
            $table->boolean('clean_all_air_filters')->nullable();
            $table->boolean('check_water_pressure')->nullable();
            $table->boolean('check_grease_point')->nullable();
            $table->foreignId('task_id')->nullable()->constrained('daily_tasks')->nullOnDelete();
            $table->foreignId('machine_id')->nullable()->constrained('machines')->nullOnDelete();
            $table->foreignId('shift_id')->nullable()->constrained('shifts')->nullOnDelete();
            $table->foreignId('login_detail_id')->nullable()->constrained('login_details')->nullOnDelete();
            $table->text('remark')->nullable();
            $table->boolean('status')->default(true);
            $table->foreignId('created_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            
            $table->index(['machine_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_maintained_data');
    }
};
