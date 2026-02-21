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
            Schema::create('cart', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('session_id');
            $table->enum('item_type', ['flight', 'event', 'package']);
            $table->unsignedBigInteger('item_id');
            $table->integer('quantity')->default(1);
            $table->string('seat_class', 50)->nullable();
            $table->foreignId('seat_zone_id')->nullable()->constrained('event_seat_zones')->onDelete('set null');
            $table->date('travel_date')->nullable();
            $table->integer('passenger_count')->default(1);
            $table->decimal('price', 10, 2);
            $table->json('options')->nullable()->comment('Additional options selected');
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('session_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart');
    }
};
