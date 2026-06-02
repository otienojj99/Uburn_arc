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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();

            // Order reference (human readable)
            $table->string('order_number')->unique();

            // Customer relationship
            $table->foreignId('customer_id')
                ->constrained()
                ->onDelete('restrict'); // Prevent deletion if sales exist

            // Dates
            $table->date('order_date')->default(now());
            $table->date('required_date')->nullable(); // Customer requested date
            $table->date('shipped_date')->nullable();
            $table->date('delivered_date')->nullable();

            // Status
            $table->enum('status', [
                'draft',
                'pending',        // Awaiting processing
                'processing',      // Being prepared
                'shipped',
                'delivered',
                'completed',
                'cancelled',
                'refunded',
                'on_hold'
            ])->default('draft');

            // Financial totals
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('tax', 15, 2)->default(0);
            $table->decimal('shipping_cost', 15, 2)->default(0);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->string('currency')->default('NGN');

            // Payment
            $table->enum('payment_status', [
                'pending',
                'paid',
                'partial',
                'refunded',
                'failed'
            ])->default('pending');

            $table->string('payment_method')->nullable(); // cash, card, transfer, etc.
            $table->string('transaction_id')->nullable(); // Gateway transaction ID

            // Shipping
            $table->string('shipping_method')->nullable();
            $table->string('tracking_number')->nullable();

            // Addresses (could also be foreign keys to addresses table)
            $table->text('shipping_address')->nullable();
            $table->text('billing_address')->nullable();

            // Additional notes
            $table->text('customer_notes')->nullable(); // From customer
            $table->text('staff_notes')->nullable();    // Internal notes

            // Staff who handled the order
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');

            // Metadata
            $table->json('metadata')->nullable(); // For extra data (e.g., source, custom fields)

            $table->timestamps();
            $table->softDeletes(); // Archive instead of delete

            // Indexes
            $table->index('order_number');
            $table->index('customer_id');
            $table->index('status');
            $table->index('order_date');
            $table->index('payment_status');
            $table->index('shipped_date');

            // Composite indexes
            $table->index(['customer_id', 'status']);
            $table->index(['status', 'order_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
