<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('api_configurations', function (Blueprint $table) {
            $table->id();
            $table->string('provider', 50); // 'amadeus', 'sabre', etc.
            $table->string('api_key')->nullable();
            $table->string('api_secret')->nullable();
            $table->string('endpoint_url');
            $table->boolean('is_production')->default(false);
            $table->boolean('is_active')->default(true);
            $table->json('additional_config')->nullable();
            $table->timestamps();
            
            $table->index('provider');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_configurations');
    }
};
