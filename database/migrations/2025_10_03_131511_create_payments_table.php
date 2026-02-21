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
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('transaction_id')->unique();
            $table->enum('payment_method', ['credit_card', 'mobile_money', 'bank_transfer', 'paypal', 'stripe']);
            $table->string('payment_provider', 100)->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('XOF');
            $table->decimal('exchange_rate', 10, 6)->default(1.000000);
            $table->decimal('amount_in_base_currency', 10, 2)->nullable();
            $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'refunded'])->default('pending');
            $table->timestamp('payment_date')->nullable();
            $table->timestamp('refund_date')->nullable();
            $table->decimal('refund_amount', 10, 2)->nullable();
            $table->json('payment_details')->nullable()->comment('Additional payment information');
            $table->text('failure_reason')->nullable();
            $table->timestamps();
            
            $table->index('transaction_id');
            $table->index('status');
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
