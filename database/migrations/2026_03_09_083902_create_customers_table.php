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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            // Account Information
            $table->string('customer_code')->unique(); // Your internal code (e.g., "CUST-001")
            $table->enum('customer_type', ['individual', 'business', 'wholesale'])->default('individual');
            $table->string('business_name')->nullable(); // For business/wholesale customers

            // Personal Information
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('alternative_phone')->nullable();

            // Password & Authentication (if they'll have login access)
            $table->string('password')->nullable(); // Nullable if you want to create customers manually first
            $table->rememberToken();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('verification_token')->nullable();

            // Address Information (primary address)
            $table->string('address_line1')->nullable();
            $table->string('address_line2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->default('Nigeria');

            // Additional addresses can be in a separate customer_addresses table later

            // Business Details (for B2B customers)
            $table->string('tax_number')->nullable(); // VAT/Tax ID for businesses
            $table->string('registration_number')->nullable(); // Company registration
            $table->string('website')->nullable();

            // Account Settings
            $table->string('currency')->default('NGN');
            $table->string('language')->default('en');

            // Payment & Credit (for wholesale/B2B)
            $table->decimal('credit_limit', 15, 2)->nullable();
            $table->integer('payment_due_days')->default = 0; // 0 for immediate payment
            $table->decimal('account_balance', 15, 2)->default = 0;

            // Loyalty & Marketing
            $table->integer('loyalty_points')->default = 0;
            $table->string('loyalty_tier')->default = 'bronze'; // bronze, silver, gold, platinum
            $table->boolean('marketing_emails')->default(true);
            $table->boolean('marketing_sms')->default(false);
            $table->datetime('last_login_at')->nullable();
            $table->string('last_login_ip')->nullable();

            // Status
            $table->enum('status', ['active', 'inactive', 'blocked', 'pending'])->default('active');
            $table->text('blocked_reason')->nullable();

            // Notes & Metadata
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable(); // For custom fields
            $table->json('preferences')->nullable(); // Customer preferences

            // Tracking
            $table->foreignId('created_by')->nullable()->constrained('users'); // Staff who created this
            $table->foreignId('updated_by')->nullable()->constrained('users');

            // Timestamps
            $table->timestamps();
            $table->softDeletes(); // Instead of permanent deletion


            // Indexes for fast searching
            $table->index('customer_code');
            $table->index('first_name');
            $table->index('last_name');
            $table->index('email');
            $table->index('phone');
            $table->index('customer_type');
            $table->index('status');
            $table->index('loyalty_tier');
            $table->index('city');
            $table->index('country');

            // Composite indexes for common searches
            $table->index(['last_name', 'first_name']);
            $table->index(['status', 'customer_type']);
            $table->index(['loyalty_tier', 'loyalty_points']);
            $table->index(['email_verified_at', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
