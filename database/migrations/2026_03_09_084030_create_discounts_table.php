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
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            // Basic Information
            $table->string('name');
            $table->string('code')->unique()->nullable(); // Promo code (nullable for automatic discounts)
            $table->string('description')->nullable();

            // Discount Type & Value
            $table->enum('type', [
                'percentage',      // 20% off
                'fixed_amount',    // ₦500 off
                'buy_x_get_y',     // BOGO
                'free_shipping',   // Free delivery
                'fixed_price'      // Set specific price (e.g., ₦1000)
            ]);

            $table->decimal('value', 15, 2); // Percentage or amount
            $table->decimal('maximum_discount_amount', 15, 2)->nullable(); // Max discount for percentage type

            // BOGO Specific Fields
            $table->integer('buy_quantity')->nullable(); // Buy X
            $table->integer('get_quantity')->nullable(); // Get Y
            $table->foreignId('free_product_id')->nullable()->constrained('product_items');

            // Eligibility & Restrictions
            $table->enum('applies_to', [
                'all_products',
                'specific_products',
                'specific_categories',
                'specific_customers',
                'first_purchase',
                'minimum_purchase'
            ])->default('all_products');

            $table->decimal('minimum_order_amount', 15, 2)->nullable();
            $table->integer('minimum_quantity')->nullable();
            $table->integer('maximum_quantity')->nullable();

            // Customer eligibility
            $table->enum('customer_eligibility', [
                'all',
                'registered_only',
                'guest_only',
                'specific_groups',
                'new_customers_only'
            ])->default('all');

            $table->json('eligible_customer_groups')->nullable(); // For specific groups
            $table->json('eligible_customer_ids')->nullable(); // For specific customers

            // Product/Category restrictions
            $table->json('eligible_product_ids')->nullable();
            $table->json('eligible_category_ids')->nullable();
            $table->json('excluded_product_ids')->nullable();
            $table->json('excluded_category_ids')->nullable();

            // Usage Limits
            $table->integer('usage_limit_per_coupon')->nullable(); // Total uses
            $table->integer('usage_limit_per_customer')->nullable(); // Per customer
            $table->integer('total_used')->default = 0;

            // Stacking Rules
            $table->boolean('can_combine_with_other_discounts')->default(false);
            $table->integer('priority')->default = 0; // Higher priority applied first
            $table->enum('stacking_type', [
                'no_stacking',
                'stack_sequential',    // Apply one after another
                'best_only'            // Only best discount applies
            ])->default('no_stacking');

            // Schedule
            $table->timestamp('starts_at');
            $table->timestamp('ends_at')->nullable();
            $table->timestamp('last_used_at')->nullable();

            // Days/Hours restrictions
            $table->json('valid_days_of_week')->nullable(); // [1,2,3,4,5] for Mon-Fri
            $table->time('valid_from_time')->nullable();
            $table->time('valid_to_time')->nullable();

            // Status
            $table->boolean('is_active')->default(true);
            $table->boolean('is_public')->default(true); // Show on website or internal only
            $table->enum('status', ['draft', 'active', 'expired', 'disabled'])->default('draft');

            // Display & Marketing
            $table->string('display_text')->nullable(); // "SAVE20" type text
            $table->string('banner_image')->nullable();
            $table->boolean('show_on_product_page')->default(false);
            $table->boolean('show_on_cart_page')->default(true);
            $table->boolean('show_on_checkout')->default(true);

            // First time purchase only
            $table->boolean('first_purchase_only')->default(false);
            $table->boolean('new_registration_only')->default(false);

            // User tracking
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');

            // Metadata
            $table->json('metadata')->nullable();
            $table->text('notes')->nullable();

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Indexes for fast lookups
            $table->index('code');
            $table->index('type');
            $table->index('is_active');
            $table->index('status');
            $table->index('starts_at');
            $table->index('ends_at');
            $table->index(['code', 'is_active']);
            $table->index(['starts_at', 'ends_at', 'is_active']);

            // Composite indexes for validation queries
            $table->index(['applies_to', 'is_active']);
            $table->index(['customer_eligibility', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
