<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('flights_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->string('pnr')->nullable();
            $table->string('eticket_number')->nullable();
            $table->string('booking_token')->nullable();
            $table->string('departure_token')->nullable();
            
            // Informations du vol
            $table->json('flight_details'); // Tous les détails du vol
            $table->json('flight_segments'); // Segments de vol
            $table->json('passenger_info'); // Infos passagers
            $table->json('booking_options')->nullable(); // Options de réservation disponibles
            
            // Prix
            $table->decimal('base_price', 10, 2);
            $table->decimal('taxes', 10, 2)->default(0);
            $table->decimal('margin_amount', 10, 2)->default(0);
            $table->decimal('margin_percentage', 5, 2)->default(0);
            $table->decimal('final_price', 10, 2);
            $table->string('currency', 3)->default('EUR');
            
            // Statuts
            $table->enum('ticket_status', ['pending', 'confirmed', 'issued', 'cancelled'])->default('pending');
            $table->string('ticket_pdf_path')->nullable();
            $table->text('cancellation_reason')->nullable();
            
            // Dates
            $table->timestamp('issued_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();
            
            $table->index('pnr');
            $table->index('ticket_status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('flights_bookings');
    }
};