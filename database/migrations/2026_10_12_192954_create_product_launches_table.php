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
        Schema::create('product_launches', function (Blueprint $table) {
             $table->id();

            $table->foreignId('product_item_id')
                ->nullable()
                ->constrained('product_items')
                ->nullOnDelete();

            // Marketing content
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->string('cta_text')->default('Notify Me');
            $table->string('cta_url')->nullable();

            // Hero image (path only — resolved by storage_url())
            $table->string('hero_image_path')->nullable();

            // Timing
            $table->timestamp('launches_at');
            $table->timestamp('ends_at')->nullable();

            // Lifecycle
            $table->enum('status', ['draft', 'scheduled', 'live', 'archived'])
                ->default('draft');
            $table->boolean('is_active')->default(true);
            $table->integer('priority')->default(0);

            $table->jsonb('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'is_active', 'priority']);
            $table->index('launches_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_launches');
    }
};
