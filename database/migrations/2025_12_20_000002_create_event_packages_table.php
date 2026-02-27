<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('package_name_fr');
            $table->string('package_name_en')->nullable();
            $table->string('package_code')->nullable();
            $table->text('description_fr')->nullable();
            $table->text('description_included_fr')->nullable();
            $table->text('description_included_en')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->string('currency', 10)->default('XAF');
            $table->integer('available_quantity')->default(0);
            $table->integer('max_per_order')->default(10);
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_packages');
    }
};

