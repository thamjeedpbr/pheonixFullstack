<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('forms', function (Blueprint $table) {
            $table->id();
            $table->integer('no_of_labours')->nullable();
            $table->integer('UPS')->nullable();
            $table->string('bad_quantity')->nullable();
            $table->string('msi_id')->unique()->nullable();
            $table->string('good_quantity')->nullable();
            $table->string('total_quantity')->nullable();
            $table->string('form_name')->nullable();
            $table->string('sheet_quantity')->nullable();
            $table->string('glmp_speed')->nullable();
            $table->text('description')->nullable();
            $table->string('mr_time')->nullable();
            $table->string('sheet_size')->nullable();
            $table->string('wast_qty')->nullable();
            $table->string('mr_speed')->nullable();
            $table->string('excepted_qty')->nullable();
            $table->string('multiply_on_save')->nullable();
            $table->boolean('coating')->default(false);
            $table->boolean('is_manual')->default(false);
            $table->date('schedule_date');
            $table->boolean('quality_verified')->default(false);
            $table->boolean('verified')->default(false);
            $table->boolean('status')->default(true);
            $table->boolean('line_cleared_success')->default(false);
            $table->enum('form_side', ['front', 'back', 'both', 'other'])->default('other');
            $table->enum('started_From', ['dmi', 'manual', 'other'])->default('other');
            $table->enum('form_status', ['job_started', 'job_pending', 'job_completed', 'other'])->default('job_pending');
            $table->enum('job_process', ['make_ready', 'start', 'job_started', 'stop', 'close'])->nullable();
            $table->json('button_history')->nullable();
            $table->string('button_status')->nullable();
            $table->string('ongoing_process_name')->nullable();
            $table->string('stop_reason')->nullable();
            $table->string('pause_reason')->nullable();
            $table->timestamp('l_v_time')->nullable();
            
            // Foreign Keys
            $table->foreignId('material_id')->nullable()->constrained('materials')->nullOnDelete();
            $table->foreignId('section_id')->nullable()->constrained('sections')->nullOnDelete();
            $table->foreignId('button_group_id')->nullable()->constrained('button_groups')->nullOnDelete();
            $table->foreignId('login_detail_id')->nullable()->constrained('login_details')->nullOnDelete();
            $table->foreignId('created_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('q_verified_vc_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('status_id')->nullable()->constrained('statuses')->nullOnDelete();
            $table->foreignId('sub_status_id')->nullable()->constrained('sub_statuses')->nullOnDelete();
            $table->foreignId('process_id')->nullable()->constrained('processes')->nullOnDelete();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['form_status', 'schedule_date', 'status']);
            $table->index('msi_id');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forms');
    }
};
