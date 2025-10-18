<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buttons', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('button_id');
            $table->string('msi_id');
            $table->boolean('is_stop')->default(false);
            $table->boolean('status')->default(true);
            $table->foreignId('created_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            
            $table->index(['status', 'is_stop']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buttons');
    }
};
