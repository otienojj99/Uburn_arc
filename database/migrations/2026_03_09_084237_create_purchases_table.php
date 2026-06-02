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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();

            // Reference to supplier
            $table->foreignId('supplier_id')
                ->constrained()
                ->onDelete('restrict'); // Prevent deletion if purchases exist

            // Unique purchase order number (human readable)
            $table->string('purchase_number')->unique();

            // Dates
            $table->date('order_date')->default(now());          // When PO was created
            $table->date('expected_delivery_date')->nullable();
            $table->date('delivery_date')->nullable();           // Actual receipt date

            // Status
            $table->enum('status', [
                'draft',
                'pending_approval',
                'approved',
                'ordered',           // Sent to supplier
                'partially_received',
                'received',
                'cancelled',
                'rejected'
            ])->default('draft');

            // Financial totals
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('tax', 15, 2)->default(0);
            $table->decimal('shipping_cost', 15, 2)->default(0);
            $table->decimal('other_cost', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->string('currency')->default('NGN');

            // Payment
            $table->string('payment_terms')->nullable(); // e.g., Net 30
            $table->enum('payment_status', [
                'pending',
                'partial',
                'paid',
                'overdue'
            ])->default('pending');

            // Shipping / delivery info
            $table->string('shipping_method')->nullable();
            $table->string('tracking_number')->nullable();
            $table->text('shipping_address')->nullable();

            // Additional notes
            $table->text('notes')->nullable();
            $table->text('internal_notes')->nullable(); // private notes

            // Approval workflow
            $table->foreignId('requested_by')->nullable()->constrained('users');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();

            // Audit
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');

            // Metadata
            $table->json('metadata')->nullable(); // For additional custom fields

            // Timestamps
            $table->timestamps();
            $table->softDeletes(); // Allow archiving instead of deletion

            // Indexes
            $table->index('purchase_number');
            $table->index('supplier_id');
            $table->index('status');
            $table->index('order_date');
            $table->index('expected_delivery_date');
            $table->index('payment_status');

            // Composite indexes for common searches
            $table->index(['supplier_id', 'status']);
            $table->index(['status', 'order_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
