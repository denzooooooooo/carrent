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
            Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('title_fr');
            $table->string('title_en');
            $table->string('slug')->unique();
            $table->text('description_fr')->nullable();
            $table->text('description_en')->nullable();
            $table->enum('event_type', ['sport', 'concert', 'theater', 'festival', 'other']);
            $table->string('sport_type', 100)->nullable()->comment('tennis, football, formula1, etc.');
            $table->string('venue_name');
            $table->text('venue_address')->nullable();
            $table->string('city', 100);
            $table->string('country', 100);
            $table->date('event_date');
            $table->time('event_time');
            $table->date('end_date')->nullable();
            $table->time('end_time')->nullable();
            $table->string('image')->nullable();
            $table->json('gallery')->nullable()->comment('Array of images');
            $table->string('video_url')->nullable();
            $table->string('organizer')->nullable();
            $table->decimal('min_price', 10, 2)->nullable();
            $table->decimal('max_price', 10, 2)->nullable();
            $table->integer('total_seats')->default(0);
            $table->integer('available_seats')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->string('meta_title_fr')->nullable();
            $table->string('meta_title_en')->nullable();
            $table->text('meta_description_fr')->nullable();
            $table->text('meta_description_en')->nullable();
            $table->timestamps();
            
            $table->index('slug');
            $table->index('event_date');
            $table->index('event_type');
            $table->index('city');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
