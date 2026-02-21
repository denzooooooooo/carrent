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
            Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_number', 50)->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('booking_type', ['flight', 'event', 'package']);
            $table->foreignId('flight_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('event_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('package_id')->nullable()->constrained('tour_packages')->onDelete('set null');
            $table->foreignId('seat_zone_id')->nullable()->constrained('event_seat_zones')->onDelete('set null');
            $table->timestamp('booking_date')->useCurrent();
            $table->date('travel_date')->nullable();
            $table->integer('number_of_passengers')->default(1);
            $table->json('passenger_details')->nullable()->comment('Array of passenger information');
            $table->string('seat_class', 50)->nullable()->comment('economy, business, first_class');
            $table->string('seat_numbers')->nullable();
            $table->decimal('total_amount', 10, 2);
            $table->string('currency', 3)->default('XOF');
            $table->decimal('discount_amount', 10, 2)->default(0.00);
            $table->decimal('tax_amount', 10, 2)->default(0.00);
            $table->decimal('final_amount', 10, 2);
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed', 'refunded'])->default('pending');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->text('special_requests')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();
            
            $table->index('booking_number');
            $table->index('user_id');
            $table->index('status');
            $table->index('booking_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
