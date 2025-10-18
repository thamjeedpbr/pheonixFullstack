<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('statuses', function (Blueprint $table) {
            $table->id();
            $table->string('Status_code')->unique();
            $table->string('Status_name');
            $table->text('remark')->nullable();
            $table->boolean('status')->default(true);
            $table->string('img')->nullable();
            $table->enum('status_type', ['stop', 'general', 'other'])->default('other');
            $table->foreignId('created_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            
            $table->index(['status', 'status_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('statuses');
    }
};
