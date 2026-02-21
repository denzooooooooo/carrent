<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pricing_rules', function (Blueprint $table) {
            $table->id();
            $table->enum('product_type', ['flight', 'event', 'package']);
            $table->string('rule_name', 100);
            $table->string('category')->nullable();
            $table->enum('margin_type', ['percentage', 'fixed']);
            $table->decimal('margin_value', 10, 2);
            $table->decimal('min_price', 10, 2)->nullable();
            $table->decimal('max_price', 10, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('priority')->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->index(['product_type', 'is_active']);
            $table->index('priority');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pricing_rules');
    }
};
