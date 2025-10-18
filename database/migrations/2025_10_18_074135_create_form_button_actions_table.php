<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('form_button_actions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('form_id')->constrained('forms')->cascadeOnDelete();
            $table->foreignId('button_group_id')->constrained('button_groups')->cascadeOnDelete();
            $table->enum('action', ['START', 'STOP', 'PAUSE', 'COMPLETE']);
            $table->text('reason')->nullable();
            $table->foreignId('performed_by_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('login_detail_id')->nullable()->constrained('login_details')->nullOnDelete();
            $table->timestamps();
            
            $table->index(['form_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('form_button_actions');
    }
};
