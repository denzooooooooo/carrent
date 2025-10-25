<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique();
            $table->string('local_code', 10)->nullable();
            $table->string('name');
            $table->string('continent', 5)->nullable();
            $table->string('iso_country', 5);
            $table->string('wikipedia_link')->nullable();
            $table->text('keywords')->nullable();
            $table->timestamps();

            $table->foreign('iso_country')->references('code')->on('countries')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regions');
    }
};
