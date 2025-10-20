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
        // Ajouter colonne is_test aux bookings
        Schema::table('bookings', function (Blueprint $table) {
            $table->boolean('is_test')->default(false)->after('status');
        });

        // Ajouter colonne is_test aux users
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_test')->default(false)->after('is_active');
        });

        // Ajouter colonne is_test aux payments
        Schema::table('payments', function (Blueprint $table) {
            $table->boolean('is_test')->default(false)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('is_test');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_test');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('is_test');
        });
    }
};
