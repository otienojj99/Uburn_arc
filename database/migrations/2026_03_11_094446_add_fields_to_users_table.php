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
        Schema::table('users', function (Blueprint $table) {
            //

            // Personal Information
            $table->string('first_name')->nullable()->after('name');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('phone')->nullable()->after('email');
            $table->string('profile_photo')->nullable()->after('phone');

            // Role & Permissions (simple role column – you can also use a roles table)
            $table->string('role')->default('staff')->after('profile_photo'); // admin, manager, staff

            // Status
            $table->boolean('is_active')->default(true)->after('role');

            // Warehouse assignment (if user is restricted to a specific warehouse)
            $table->foreignId('warehouse_id')
                ->nullable()
                ->after('is_active')
                ->constrained()
                ->nullOnDelete();

            // Tracking
            $table->foreignId('created_by')
                ->nullable()
                ->after('warehouse_id')
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('updated_by')
                ->nullable()
                ->after('created_by')
                ->constrained('users')
                ->nullOnDelete();

            // Last login info
            $table->timestamp('last_login_at')->nullable()->after('updated_by');
            $table->string('last_login_ip')->nullable()->after('last_login_at');

            // Preferences (JSON for user settings)
            $table->json('preferences')->nullable()->after('last_login_ip');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->dropColumn([
                'first_name',
                'last_name',
                'phone',
                'profile_photo',
                'role',
                'is_active',
                'warehouse_id',
                'created_by',
                'updated_by',
                'last_login_at',
                'last_login_ip',
                'preferences',
            ]);
        });
    }
};
