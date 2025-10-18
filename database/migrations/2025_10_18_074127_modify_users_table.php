<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Remove default Laravel columns we don't need
            $table->dropColumn('email');
            $table->dropColumn('email_verified_at');
            
            // Add Phoenix system columns
            $table->string('user_name')->unique()->after('id');
            $table->string('phone_no')->nullable()->after('name');
            $table->boolean('status')->default(true)->after('password');
            $table->boolean('is_super_user')->default(false)->after('status');
            $table->string('api_key')->nullable()->after('is_super_user');
            $table->foreignId('permission_id')->nullable()->constrained('user_permissions')->nullOnDelete()->after('api_key');
            $table->foreignId('login_detail_id')->nullable()->constrained('login_details')->nullOnDelete()->after('permission_id');
            $table->timestamp('last_login_time')->nullable()->after('login_detail_id');
            $table->enum('user_type', ['operator', 'super_wiser', 'admin'])->default('operator')->after('last_login_time');
            
            // Add indexes
            $table->index(['status', 'user_type']);
            $table->index('user_name');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['permission_id']);
            $table->dropForeign(['login_detail_id']);
            $table->dropIndex(['status', 'user_type']);
            $table->dropIndex(['user_name']);
            
            $table->dropColumn([
                'user_name',
                'phone_no',
                'status',
                'is_super_user',
                'api_key',
                'permission_id',
                'login_detail_id',
                'last_login_time',
                'user_type',
            ]);
            
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
        });
    }
};
