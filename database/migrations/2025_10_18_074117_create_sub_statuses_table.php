<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sub_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('sub_status_name')->unique();
            $table->string('sub_status_code')->unique();
            $table->string('hours_kpi')->nullable();
            $table->string('sub_status_kpi')->nullable();
            $table->boolean('active')->default(true);
            $table->text('sub_status_remark')->nullable();
            $table->boolean('non_sellable')->default(true);
            $table->string('img')->nullable();
            $table->foreignId('status_id')->constrained('statuses')->cascadeOnDelete();
            $table->foreignId('machine_type_id')->nullable()->constrained('machine_types')->nullOnDelete();
            $table->foreignId('created_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            
            $table->index(['active', 'status_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sub_statuses');
    }
};
