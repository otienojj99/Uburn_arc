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
        Schema::create('stock_transfers', function (Blueprint $table) {
            $table->id();

            // Unique transfer reference (human readable)
            $table->string('transfer_number')->unique();

            // Source and destination warehouses
            $table->foreignId('source_warehouse_id')
                ->constrained('warehouses')
                ->onDelete('restrict');

            $table->foreignId('destination_warehouse_id')
                ->constrained('warehouses')
                ->onDelete('restrict');

            // Status tracking
            $table->enum('status', [
                'draft',
                'pending',      // Awaiting approval
                'approved',
                'in_transit',   // Stock removed from source but not yet received at destination
                'completed',    // Fully received at destination
                'cancelled'
            ])->default('draft');

            // Dates
            $table->date('request_date')->default(now());
            $table->date('transfer_date')->nullable();      // When goods left source
            $table->date('expected_arrival_date')->nullable();
            $table->date('completed_date')->nullable();     // When fully received

            // Quantities (denormalized for quick overview, can be calculated from items)
            $table->integer('total_items_count')->default(0);      // Number of distinct products
            $table->integer('total_quantity')->default(0);         // Total units being transferred

            // Notes & metadata
            $table->text('reason')->nullable();              // Why the transfer is needed
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable();            // Extra data (e.g., carrier info, temperature requirements)

            // Approval workflow
            $table->foreignId('requested_by')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');

            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');

            $table->foreignId('completed_by')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');

            // Audit
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('transfer_number');
            $table->index('source_warehouse_id');
            $table->index('destination_warehouse_id');
            $table->index('status');
            $table->index('request_date');
            $table->index('expected_arrival_date');

            // Composite indexes
            $table->index(['source_warehouse_id', 'status']);
            $table->index(['destination_warehouse_id', 'status']);
            $table->index(['status', 'request_date']);

            // Ensure source and destination are not the same
            // This is a check constraint (if your DB supports it, otherwise enforce in code)
            // $table->check('source_warehouse_id != destination_warehouse_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_transfers');
    }
};
