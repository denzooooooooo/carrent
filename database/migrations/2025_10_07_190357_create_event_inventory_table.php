<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_inventory', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->foreignId('seat_zone_id')->nullable()->constrained('event_seat_zones');
            $table->integer('total_tickets');
            $table->integer('sold_tickets')->default(0);
            $table->integer('reserved_tickets')->default(0);
            $table->integer('available_tickets');
            $table->timestamp('last_updated')->useCurrent()->useCurrentOnUpdate();
            $table->timestamps();
            
            $table->index(['event_id', 'seat_zone_id']);
            $table->index('available_tickets');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_inventory');
    }
};
