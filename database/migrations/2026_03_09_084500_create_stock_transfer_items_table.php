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
        Schema::create('stock_transfer_items', function (Blueprint $table) {
            $table->id();

            // Link to the parent stock transfer
            $table->foreignId('stock_transfer_id')
                ->constrained()
                ->onDelete('cascade'); // If transfer deleted, remove its items

            // The product being transferred
            $table->foreignId('product_item_id')
                ->constrained()
                ->onDelete('restrict'); // Prevent deletion if referenced in transfers

            // Quantities
            $table->integer('quantity_requested');       // How many were supposed to be transferred
            $table->integer('quantity_shipped')->default(0);   // How many actually left source warehouse
            $table->integer('quantity_received')->default(0);   // How many arrived at destination

            // Status of this line item
            $table->enum('status', [
                'pending',      // Not yet processed
                'shipped',      // Shipped from source, not yet received
                'partial',      // Partial shipment/receipt
                'received',     // Fully received at destination
                'cancelled'     // Line cancelled
            ])->default('pending');

            // Optional fields
            $table->json('metadata')->nullable(); // For extra data like batch numbers, serials

            // Audit
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');

            $table->timestamps();

            // Indexes for performance
            $table->index('stock_transfer_id');
            $table->index('product_item_id');
            $table->index('status');

            // Ensure each product appears only once per transfer
            $table->unique(['stock_transfer_id', 'product_item_id'], 'transfer_item_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_transfer_items');
    }
};
