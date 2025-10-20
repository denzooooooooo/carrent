<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('package_inventory', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained('tour_packages')->onDelete('cascade');
            $table->date('available_date');
            $table->integer('max_participants');
            $table->integer('booked_participants')->default(0);
            $table->integer('available_spots');
            $table->decimal('price_override', 10, 2)->nullable();
            $table->boolean('is_available')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['package_id', 'available_date']);
            $table->index('available_date');
            $table->index('is_available');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('package_inventory');
    }
};
