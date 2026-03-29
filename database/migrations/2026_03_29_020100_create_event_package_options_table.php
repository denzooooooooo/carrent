<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('event_package_options')) {
            return;
        }

        Schema::create('event_package_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_package_id')->constrained('event_packages')->onDelete('cascade');
            $table->string('option_label_fr');
            $table->string('option_label_en')->nullable();
            $table->string('option_context_fr')->nullable();
            $table->string('option_context_en')->nullable();
            $table->date('option_date')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->string('currency', 10)->default('XOF');
            $table->unsignedInteger('available_quantity')->default(0);
            $table->unsignedInteger('max_per_order')->default(10);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['event_package_id', 'is_active']);
            $table->index('option_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_package_options');
    }
};
