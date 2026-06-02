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
        Schema::table('product_variants', function (Blueprint $table) {
            // Drop the old image column if it exists
            if (Schema::hasColumn('product_variants', 'image')) {
                $table->dropColumn('image');
            }

            // Add the new foreign key
            $table->foreignId('image_id')
                ->nullable()
                ->after('stock') // optional – place it where you want
                ->constrained('product_images')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            // Drop the foreign key and column
            $table->dropForeign(['image_id']);
            $table->dropColumn('image_id');

            // Re-add the original image column (string, nullable)
            $table->string('image')->nullable();
        });
    }
};
