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
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('name_fr');
            $table->string('name_en');
            $table->text('description_fr')->nullable();
            $table->text('description_en')->nullable();
            $table->string('category'); // terrestre, aérien, nautique
            $table->string('type'); // voiture, quad, avion, bateau, etc.
            $table->decimal('price_per_day', 10, 2);
            $table->integer('capacity')->default(1);
            $table->string('image')->nullable();
            $table->json('features')->nullable(); // caractéristiques
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
