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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();

            // Company Information
            $table->string('company_name');
            $table->string('trade_name')->nullable();
            $table->string('supplier_code')->unique(); // Your internal code
            $table->string('tax_number')->nullable(); // VAT/Tax ID
            $table->string('registration_number')->nullable(); // Company registration



            // Contact Information
            $table->string('contact_person')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('fax')->nullable();
            $table->string('website')->nullable();

            // Address
            $table->string('address_line1')->nullable();
            $table->string('address_line2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->default('Kenya');

            // Payment & Terms
            $table->string('payment_terms')->nullable(); // e.g., "Net 30", "COD"
            $table->string('currency')->default('NGN');
            $table->decimal('credit_limit', 15, 2)->nullable();
            $table->integer('payment_due_days')->default = 30;

            // Banking Details
            $table->string('bank_name')->nullable();
            $table->string('bank_account_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_sort_code')->nullable();
            $table->string('bank_swift_code')->nullable();


            // Product/Category specializations
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable(); // For custom fields

            // Status & Tracking
            $table->boolean('is_active')->default(true);
            $table->boolean('is_preferred')->default(false);
            $table->enum('rating', [1, 2, 3, 4, 5])->nullable(); // Supplier rating

            $table->timestamps();
            $table->softDeletes(); // Allow archiving instead of deleting

            // Indexes for fast searching
            $table->index('supplier_code');
            $table->index('company_name');
            $table->index('email');
            $table->index('phone');
            $table->index('is_active');
            $table->index('rating');
            $table->index('country');
            $table->index('city');

            // Composite indexes for common searches
            $table->index(['is_active', 'rating']);
            $table->index(['country', 'city']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
