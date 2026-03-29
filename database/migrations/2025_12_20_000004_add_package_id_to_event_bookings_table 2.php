<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Ajoute package_id et rend zone_id nullable pour supporter les réservations de packages
     */
    public function up(): void
    {
        if (!Schema::hasTable('event_bookings') || Schema::hasColumn('event_bookings', 'package_id')) {
            return;
        }

        Schema::table('event_bookings', function (Blueprint $table) {
            // Rendre zone_id nullable pour permettre les réservations de packages
            $table->foreignId('zone_id')->nullable()->change();
            
            // Ajouter package_id pour les réservations de packages
            $table->foreignId('package_id')->nullable()->constrained('event_packages')->onDelete('cascade')->after('zone_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('event_bookings') || !Schema::hasColumn('event_bookings', 'package_id')) {
            return;
        }

        Schema::table('event_bookings', function (Blueprint $table) {
            $table->foreignId('zone_id')->constrained('event_seat_zones')->onDelete('cascade')->change();
            $table->dropForeign(['package_id']);
            $table->dropColumn('package_id');
        });
    }
};
