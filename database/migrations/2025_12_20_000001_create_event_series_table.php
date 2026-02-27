<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_series', function (Blueprint $table) {
            $table->id();
            $table->string('name_fr');
            $table->string('name_en')->nullable();
            $table->string('slug')->unique();
            $table->text('description_fr')->nullable();
            $table->text('description_en')->nullable();
            $table->string('venue_name')->nullable();
            $table->string('venue_address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('main_image')->nullable();
            $table->string('cover_image')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('organizer')->nullable();
            $table->string('sport_type')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_series');
    }
};

