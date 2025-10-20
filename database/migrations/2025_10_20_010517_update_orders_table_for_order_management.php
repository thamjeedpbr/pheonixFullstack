<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Add new columns for Order Management
            $table->string('title')->after('client_name')->nullable();
            $table->text('description')->after('title')->nullable();
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->after('description')->default('normal');
            
            // Change status from boolean to enum
            $table->dropColumn('status');
        });
        
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->after('priority')->default('pending');
        });
        
        // Make some fields nullable that might not be needed initially
        Schema::table('orders', function (Blueprint $table) {
            $table->text('prod_details')->nullable()->change();
            $table->text('job_details')->nullable()->change();
            $table->date('production_start')->nullable()->change();
            $table->string('finish_size')->nullable()->change();
            $table->string('finish_quantity')->nullable()->change();
            $table->text('remark')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Remove new columns
            $table->dropColumn(['title', 'description', 'priority']);
            
            // Change status back to boolean
            $table->dropColumn('status');
        });
        
        Schema::table('orders', function (Blueprint $table) {
            $table->boolean('status')->default(true);
        });
        
        // Change fields back to not nullable
        Schema::table('orders', function (Blueprint $table) {
            $table->text('prod_details')->nullable(false)->change();
            $table->text('job_details')->nullable(false)->change();
            $table->date('production_start')->nullable(false)->change();
            $table->string('finish_size')->nullable(false)->change();
            $table->string('finish_quantity')->nullable(false)->change();
            $table->text('remark')->nullable(false)->change();
        });
    }
};
