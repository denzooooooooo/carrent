<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flight_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->string('pnr', 10);
            $table->string('eticket_number', 20)->nullable();
            $table->string('amadeus_booking_ref', 50)->nullable();
            $table->json('flight_segments');
            $table->decimal('base_price', 10, 2);
            $table->decimal('taxes', 10, 2)->default(0);
            $table->decimal('margin_amount', 10, 2)->default(0);
            $table->decimal('margin_percentage', 5, 2)->default(0);
            $table->decimal('final_price', 10, 2);
            $table->enum('ticket_status', ['issued', 'cancelled', 'refunded', 'pending'])->default('pending');
            $table->string('ticket_pdf_path')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->timestamp('issued_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();
            
            $table->index('pnr');
            $table->index('eticket_number');
            $table->index('ticket_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flight_bookings');
    }
};
