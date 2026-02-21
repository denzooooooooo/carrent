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
            Schema::create('carousels', function (Blueprint $table) {
            $table->id();
            $table->string('title_fr');
            $table->string('title_en');
            $table->text('subtitle_fr')->nullable();
            $table->text('subtitle_en')->nullable();
            $table->string('image');
            $table->string('mobile_image')->nullable();
            $table->string('video_url')->nullable();
            $table->string('link_url')->nullable();
            $table->string('button_text_fr', 100)->nullable();
            $table->string('button_text_en', 100)->nullable();
            $table->integer('order_position')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->timestamps();
            
            $table->index('order_position');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carousels');
    }
};
