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
            Schema::create('tour_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('title_fr');
            $table->string('title_en');
            $table->string('slug')->unique();
            $table->text('description_fr')->nullable();
            $table->text('description_en')->nullable();
            $table->enum('package_type', ['helicopter', 'private_jet', 'cruise', 'safari', 'city_tour', 'adventure', 'luxury']);
            $table->string('destination');
            $table->integer('duration')->comment('Duration in days');
            $table->string('duration_text_fr', 100)->nullable();
            $table->string('duration_text_en', 100)->nullable();
            $table->string('departure_city', 100)->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('discount_price', 10, 2)->nullable();
            $table->integer('max_participants')->default(1);
            $table->integer('min_participants')->default(1);
            $table->json('included_services_fr')->nullable()->comment('Array of included services');
            $table->json('included_services_en')->nullable();
            $table->json('excluded_services_fr')->nullable();
            $table->json('excluded_services_en')->nullable();
            $table->json('itinerary_fr')->nullable()->comment('Day by day itinerary');
            $table->json('itinerary_en')->nullable();
            $table->string('image')->nullable();
            $table->json('gallery')->nullable();
            $table->string('video_url')->nullable();
            $table->json('available_dates')->nullable()->comment('Array of available dates');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->decimal('rating', 3, 2)->default(0.00);
            $table->integer('total_reviews')->default(0);
            $table->string('meta_title_fr')->nullable();
            $table->string('meta_title_en')->nullable();
            $table->text('meta_description_fr')->nullable();
            $table->text('meta_description_en')->nullable();
            $table->timestamps();
            
            $table->index('slug');
            $table->index('package_type');
            $table->index('destination');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tour_packages');
    }
};
