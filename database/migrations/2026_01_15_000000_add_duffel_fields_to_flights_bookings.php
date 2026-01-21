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
        Schema::table('flights_bookings', function (Blueprint $table) {
            $table->string('duffel_offer_id')->nullable();
            $table->string('duffel_order_id')->nullable();
            $table->string('duffel_booking_ref')->nullable();
            $table->timestamp('duffel_confirmed_at')->nullable();
            $table->string('payment_split_status')->nullable();
            $table->json('duffel_conditions')->nullable();
            $table->string('duffel_customer_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('flights_bookings', function (Blueprint $table) {
            $table->dropColumn([
                'duffel_offer_id',
                'duffel_order_id',
                'duffel_booking_ref',
                'duffel_confirmed_at',
                'payment_split_status',
                'duffel_conditions',
                'duffel_customer_id',
            ]);
        });
    }
};

