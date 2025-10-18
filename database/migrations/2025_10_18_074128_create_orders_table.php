<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('job_card_no')->unique();
            $table->text('prod_details');
            $table->text('job_details');
            $table->date('delivery_date');
            $table->date('production_start');
            $table->string('finish_size');
            $table->string('finish_quantity');
            $table->text('remark');
            $table->string('client_name');
            $table->string('pro_name')->nullable();
            $table->string('ref_no')->nullable();
            $table->boolean('status')->default(true);
            $table->boolean('closed')->default(true);
            $table->foreignId('material_id')->nullable()->constrained('materials')->nullOnDelete();
            $table->foreignId('process_id')->nullable()->constrained('processes')->nullOnDelete();
            $table->foreignId('created_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            
            $table->index(['status', 'delivery_date']);
            $table->index('client_name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
