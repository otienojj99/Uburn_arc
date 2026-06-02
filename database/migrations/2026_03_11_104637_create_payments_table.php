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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            // Polymorphic relation: can be used for sales, purchases, etc.
            $table->string('payable_type'); // e.g., 'App\Models\Sale'
            $table->unsignedBigInteger('payable_id');

            $table->decimal('amount', 15, 2);
            $table->date('payment_date')->default(now());
            $table->enum('payment_method', ['cash', 'card', 'bank_transfer', 'mobile_money', 'cheque', 'credit', 'other'])->default('cash');
            $table->string('reference')->nullable(); // transaction ID, cheque number, etc.
            $table->string('status')->default('completed'); // completed, pending, failed, refunded

            // Optional: link to a user (who made/received the payment)
            $table->foreignId('created_by')->nullable()->constrained('users');

            $table->text('notes')->nullable();
            $table->json('metadata')->nullable();

            $table->timestamps();

            // Indexes
            $table->index(['payable_type', 'payable_id']);
            $table->index('payment_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
