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
        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();

            // Link to the parent sale order
            $table->foreignId('sale_id')
                ->constrained()
                ->onDelete('cascade'); // If sale deleted, remove its items

            // The product being sold
            $table->foreignId('product_item_id')
                ->constrained()
                ->onDelete('restrict'); // Prevent deletion if referenced in sales

            // Quantities
            $table->integer('quantity');

            // Pricing at time of sale (historical record)
            $table->decimal('unit_price', 15, 2);           // Price per unit at time of sale
            $table->decimal('discount', 15, 2)->default(0); // Line discount (could be amount or %)
            $table->decimal('tax', 15, 2)->default(0);      // Tax amount for this line
            $table->decimal('total', 15, 2);                // Calculated total (quantity * unit_price - discount + tax)

            // Status of this line item
            $table->enum('status', [
                'pending',      // Ordered but not yet processed
                'shipped',      // Item has been shipped
                'delivered',    // Item delivered
                'returned',     // Item returned by customer
                'cancelled'     // Line cancelled
            ])->default('pending');

            // Optional fields for additional tracking
            $table->json('metadata')->nullable(); // Extra data (e.g., serial numbers, customizations)

            // Audit trail
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');

            $table->timestamps();
            $table->softDeletes(); // Optional, for preserving history

            // Indexes for performance
            $table->index('sale_id');
            $table->index('product_item_id');
            $table->index('status');
            $table->index(['sale_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_items');
    }
};
