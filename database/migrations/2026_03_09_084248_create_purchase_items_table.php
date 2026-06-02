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
        Schema::create('purchase_items', function (Blueprint $table) {
            $table->id();

            // Link to the parent purchase order
            $table->foreignId('purchase_id')
                ->constrained()
                ->onDelete('cascade'); // If purchase deleted, remove its items

            // The product being ordered
            $table->foreignId('product_item_id')
                ->constrained()
                ->onDelete('restrict'); // Prevent deletion if referenced in purchases

            // Quantities
            $table->integer('quantity_ordered');
            $table->integer('quantity_received')->default(0);

            // Pricing at time of order (historical record)
            $table->decimal('unit_cost', 15, 2);           // Price per unit
            $table->decimal('discount', 15, 2)->default(0); // Line discount
            $table->decimal('tax', 15, 2)->default(0);      // Tax amount for line
            $table->decimal('total', 15, 2);                // Calculated total (unit_cost * quantity_ordered - discount + tax)

            // Status of this line item
            $table->enum('status', [
                'pending',      // Not yet received
                'partial',      // Partially received
                'received',     // Fully received
                'cancelled'     // Cancelled line
            ])->default('pending');

            // Optional fields for additional tracking
            $table->date('expected_delivery_date')->nullable(); // Per-item expected date (if different)
            $table->json('metadata')->nullable(); // Extra data (e.g., batch numbers, serials)

            // Audit trail
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');

            $table->timestamps();
            $table->softDeletes(); // Optional, for preserving history if needed

            // Indexes for performance
            $table->index('purchase_id');
            $table->index('product_item_id');
            $table->index('status');
            $table->index(['purchase_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_items');
    }
};
