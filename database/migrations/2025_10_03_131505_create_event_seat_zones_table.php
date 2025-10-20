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
            Schema::create('event_seat_zones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('zone_name_fr', 100);
            $table->string('zone_name_en', 100);
            $table->string('zone_code', 20);
            $table->decimal('price', 10, 2);
            $table->integer('total_seats');
            $table->integer('available_seats');
            $table->text('description_fr')->nullable();
            $table->text('description_en')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['event_id', 'zone_code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_seat_zones');
    }
};
