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
            Schema::create('flights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('airline_id')->constrained()->onDelete('cascade');
            $table->string('flight_number', 20);
            $table->foreignId('departure_airport_id')->constrained('airports')->onDelete('cascade');
            $table->foreignId('arrival_airport_id')->constrained('airports')->onDelete('cascade');
            $table->date('departure_date');
            $table->time('departure_time');
            $table->date('arrival_date');
            $table->time('arrival_time');
            $table->integer('duration')->comment('Duration in minutes');
            $table->string('aircraft_type', 100)->nullable();
            $table->integer('economy_seats')->default(0);
            $table->integer('business_seats')->default(0);
            $table->integer('first_class_seats')->default(0);
            $table->decimal('economy_price', 10, 2)->nullable();
            $table->decimal('business_price', 10, 2)->nullable();
            $table->decimal('first_class_price', 10, 2)->nullable();
            $table->integer('available_economy')->default(0);
            $table->integer('available_business')->default(0);
            $table->integer('available_first_class')->default(0);
            $table->enum('status', ['scheduled', 'delayed', 'cancelled', 'completed'])->default('scheduled');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index('flight_number');
            $table->index('departure_date');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flights');
    }
};
