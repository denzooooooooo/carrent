<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('package_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->foreignId('package_id')->constrained('tour_packages')->onDelete('cascade');
            $table->string('confirmation_number', 20)->unique();
            $table->date('travel_date');
            $table->integer('participants_count');
            $table->json('participants_details');
            $table->decimal('base_price', 10, 2);
            $table->decimal('margin_amount', 10, 2)->default(0);
            $table->decimal('final_price', 10, 2);
            $table->string('voucher_pdf_path')->nullable();
            $table->string('itinerary_pdf_path')->nullable();
            $table->enum('status', ['confirmed', 'pending', 'cancelled', 'completed'])->default('pending');
            $table->text('special_requests')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();
            
            $table->index('confirmation_number');
            $table->index('status');
            $table->index('travel_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('package_bookings');
    }
};
