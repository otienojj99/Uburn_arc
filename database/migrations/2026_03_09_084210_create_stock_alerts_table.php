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
        Schema::create('stock_alerts', function (Blueprint $table) {
            $table->id();

            // Core relationships
            $table->foreignId('product_item_id')
                ->constrained()
                ->onDelete('cascade'); // Remove alerts if product deleted

            $table->foreignId('warehouse_id')
                ->nullable()
                ->constrained()
                ->onDelete('cascade'); // Nullable for global alerts

            // Alert definition
            $table->enum('alert_type', [
                'low_stock',
                'out_of_stock',
                'overstock',
                'expiry_soon',
                'reorder_point'
            ]);

            // Threshold that triggered this alert
            $table->integer('threshold_value'); // e.g., minimum_stock = 5
            $table->integer('current_value');   // Current stock level at alert time

            // Severity level
            $table->enum('severity', ['info', 'warning', 'critical'])
                ->default('warning');

            // Status flags
            $table->boolean('is_active')->default(true);   // Alert is active (not dismissed)
            $table->boolean('is_resolved')->default(false); // Manually resolved
            $table->timestamp('resolved_at')->nullable();
            $table->foreignId('resolved_by')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');
            $table->text('resolution_notes')->nullable();

            // Optional metadata
            $table->json('metadata')->nullable(); // Extra info like recommended reorder quantity

            // Who created (if system-generated, user_id may be null)
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');

            $table->timestamps();

            // ========== INDEXES ==========
            $table->index('product_item_id');
            $table->index('warehouse_id');
            $table->index('alert_type');
            $table->index('severity');
            $table->index('is_active');
            $table->index('is_resolved');
            $table->index('created_at');

            // Composite indexes for common queries
            $table->index(['product_item_id', 'is_resolved']);
            $table->index(['warehouse_id', 'is_active']);
            $table->index(['alert_type', 'is_active']);
            $table->index(['severity', 'is_resolved']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_alerts');
    }
};
