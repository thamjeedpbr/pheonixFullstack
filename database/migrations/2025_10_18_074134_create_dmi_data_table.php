<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dmi_data', function (Blueprint $table) {
            $table->id();
            $table->boolean('status')->default(true);
            $table->boolean('is_gen')->default(false);
            $table->boolean('is_manual')->default(false);
            $table->text('remark')->nullable();
            $table->string('ups')->nullable();
            $table->string('ended_status')->nullable();
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->string('speed')->nullable();
            $table->string('employee_count')->nullable();
            $table->time('time')->nullable();
            $table->string('good_lm')->nullable();
            $table->string('bad_lm')->nullable();
            $table->string('order_id')->nullable();
            $table->enum('active_description', ['good_impression', 'bad_impression', 'middle_impression', 'other'])->default('other');
            $table->enum('general_maintance', ['START', 'STOP', 'FINISH', 'other'])->default('other');
            $table->foreignId('status_id')->nullable()->constrained('statuses')->nullOnDelete();
            $table->foreignId('sub_status_id')->nullable()->constrained('sub_statuses')->nullOnDelete();
            $table->foreignId('form_id')->nullable()->constrained('forms')->nullOnDelete();
            $table->foreignId('machine_id')->nullable()->constrained('machines')->nullOnDelete();
            $table->foreignId('machine_type_id')->nullable()->constrained('machine_types')->nullOnDelete();
            $table->foreignId('created_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            
            $table->index(['form_id', 'machine_id', 'start_time']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dmi_data');
    }
};
