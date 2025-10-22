<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('processes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->comment('Process name');
            $table->string('msi_id')->nullable()->comment('MSI system ID');
            $table->boolean('status')->default(true);
            $table->foreignId('created_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            
            $table->index('status');
            $table->index('msi_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('processes');
    }
};
