<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('group_id');
            $table->string('form_id');
            $table->boolean('verified')->default(false);
            $table->string('qc_id');
            $table->string('file_path')->nullable();
            $table->foreignId('created_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            
            $table->index(['group_id', 'form_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
