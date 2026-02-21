<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->string('ticket_number', 20)->unique();
            $table->string('qr_code');
            $table->text('qr_data');
            $table->foreignId('seat_zone_id')->nullable()->constrained('event_seat_zones');
            $table->string('seat_number', 20)->nullable();
            $table->decimal('base_price', 10, 2);
            $table->decimal('margin_amount', 10, 2)->default(0);
            $table->decimal('final_price', 10, 2);
            $table->enum('ticket_status', ['valid', 'used', 'cancelled', 'expired'])->default('valid');
            $table->timestamp('used_at')->nullable();
            $table->string('used_by')->nullable();
            $table->string('ticket_pdf_path')->nullable();
            $table->timestamps();
            
            $table->index('ticket_number');
            $table->index('ticket_status');
            $table->index(['event_id', 'ticket_status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_tickets');
    }
};
