<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('manual_qc_verifications', function (Blueprint $table) {
            $table->id();
            $table->string('group_id');
            $table->foreignId('form_id')->nullable()->constrained('forms')->nullOnDelete();
            $table->foreignId('shift_id')->nullable()->constrained('shifts')->nullOnDelete();
            $table->foreignId('process_id')->nullable()->constrained('processes')->nullOnDelete();
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->foreignId('qc_master_id')->nullable()->constrained('qc_masters')->nullOnDelete();
            $table->foreignId('q_verified_vc_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('line_verified_id')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('q_verified')->default(false);
            $table->timestamp('q_v_time')->nullable();
            $table->boolean('h_verified')->default(false);
            $table->boolean('Am_1')->default(false);
            $table->boolean('Am_2')->default(false);
            $table->boolean('Am_3')->default(false);
            $table->boolean('Am_4')->default(false);
            $table->boolean('Am_5')->default(false);
            $table->text('note')->nullable();
            $table->boolean('l_verified')->default(false);
            $table->timestamp('l_v_time')->nullable();
            $table->text('history')->nullable();
            $table->text('remark')->nullable();
            $table->foreignId('verified_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('status')->default(true);
            $table->timestamps();
            
            $table->index(['form_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('manual_qc_verifications');
    }
};
