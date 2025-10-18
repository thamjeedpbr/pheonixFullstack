<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('material_id')->unique();
            $table->string('name');
            $table->string('utilization')->nullable();
            $table->boolean('coating')->default(false);
            $table->text('description')->nullable();
            $table->string('gsm')->nullable();
            $table->string('unit')->nullable();
            $table->boolean('status')->default(true);
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->foreignId('created_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            
            $table->index(['status', 'department_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
