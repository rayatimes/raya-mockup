<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('token', 32);
            $table->boolean('is_active')->nullable()->default(true);
            $table->boolean('has_cms_access')->nullable()->default(true);
            $table->boolean('can_publish')->nullable()->default(false);
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique('token');
            $table->unique('name');
            $table->index('token');
            $table->index('is_active');
            $table->index('has_cms_access');
            $table->index('can_publish');
            $table->index('name');
        });

        Schema::create('role_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('role_token', 32);
            $table->boolean('can_access')->nullable()->default(true);
            $table->boolean('can_create')->nullable()->default(true);
            $table->boolean('can_read')->nullable()->default(true);
            $table->boolean('can_update')->nullable()->default(true);
            $table->boolean('can_delete')->nullable()->default(true);
            $table->boolean('can_import')->nullable()->default(true);
            $table->boolean('can_export')->nullable()->default(true);
            $table->boolean('can_use_additional_button')->nullable()->default(true);
            $table->timestamps();

            $table->index('can_access');
            $table->index('can_create');
            $table->index('can_read');
            $table->index('can_update');
            $table->index('can_delete');
            $table->index('can_import');
            $table->index('can_export');
            $table->index('can_use_additional_button');

            $table->foreign('role_token')->references('token')->on('roles')->cascadeOnDelete();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('token', 32);
            $table->string('role_token', 32)->nullable();
            $table->enum('status', ['active', 'inactive'])->nullable()->default('active');
            $table->json('settings')->nullable();
            $table->json('links')->nullable();
            $table->string('alias')->nullable();
            $table->string('summary')->nullable();
            $table->text('bio')->nullable();
            $table->string('name');
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('phone', 15)->nullable();
            $table->string('username');
            $table->string('email');
            $table->string('password');
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->unique('token');
            $table->unique('username');
            $table->unique('email');
            $table->index('token');
            $table->index('status');
            $table->index('alias');
            $table->index('summary');
            $table->index('name');
            $table->index('gender');
            $table->index('phone');
            $table->index('username');
            $table->index('email');

            $table->foreign('role_token')->references('token')->on('roles')->nullOnDelete();
        });

        Schema::create('user_actions', function (Blueprint $table) {
            $table->id();
            $table->string('token', 32);
            $table->string('user_token', 32)->nullable();
            $table->json('user_meta_data')->nullable();
            $table->enum('action', [
                'new_account',
                'resend_verification_new_account',
                'reset_password',
                'resend_reset_password',
                'change_email',
                'resend_change_email',
                'change_username'
            ]);
            $table->string('email')->nullable();
            $table->string('new_email')->nullable();
            $table->string('username')->nullable();
            $table->string('new_username')->nullable();
            $table->boolean('is_used')->nullable()->default(false);
            $table->timestamp('expired_at');
            $table->timestamps();

            $table->unique('token');
            $table->index('token');
            $table->index('action');
            $table->index('email');
            $table->index('new_email');
            $table->index('username');
            $table->index('new_username');
            $table->index('is_used');
            $table->index('expired_at');
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->constrained('users')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
