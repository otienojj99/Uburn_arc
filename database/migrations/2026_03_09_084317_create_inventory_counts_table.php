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
        Schema::create('inventory_counts', function (Blueprint $table) {
            $table->id();

            // Unique reference (human readable)
            $table->string('count_reference')->unique();

            // Warehouse being counted
            $table->foreignId('warehouse_id')
                ->constrained()
                ->onDelete('restrict');

            // Who created/approved the count
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');

            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');

            // Status of the count
            $table->enum('status', [
                'draft',
                'in_progress',
                'completed',
                'approved',
                'cancelled'
            ])->default('draft');

            // Dates
            $table->date('count_date')->default(now());        // Scheduled date
            $table->timestamp('completed_at')->nullable();     // When marked completed

            // Additional info
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable(); // For extra data like count method, device info

            $table->timestamps();
            $table->softDeletes(); // Allow archiving

            // Indexes
            $table->index('count_reference');
            $table->index('warehouse_id');
            $table->index('status');
            $table->index('count_date');
            $table->index(['warehouse_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_counts');
    }
};
