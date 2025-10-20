<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_gateways', function (Blueprint $table) {
            $table->id();
            $table->string('gateway_name', 50);
            $table->string('gateway_type', 50);
            $table->string('api_key')->nullable();
            $table->string('api_secret')->nullable();
            $table->string('merchant_id', 100)->nullable();
            $table->string('webhook_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('supported_currencies')->nullable();
            $table->decimal('transaction_fee_percentage', 5, 2)->default(0);
            $table->decimal('transaction_fee_fixed', 10, 2)->default(0);
            $table->json('configuration')->nullable();
            $table->timestamps();
            
            $table->index('gateway_name');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_gateways');
    }
};
