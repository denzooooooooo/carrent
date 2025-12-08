<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modifier l'enum booking_type pour inclure 'location'
        DB::statement("ALTER TABLE bookings MODIFY COLUMN booking_type ENUM('flight', 'event', 'package', 'location') NOT NULL");
        
        // Ajouter la colonne location_id
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('location_id')->nullable()->after('package_id')->constrained('locations')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Supprimer la colonne location_id si elle existe
        if (Schema::hasColumn('bookings', 'location_id')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->dropForeign(['location_id']);
                $table->dropColumn('location_id');
            });
        }
        
        // Restaurer l'enum booking_type sans 'location'
        DB::statement("ALTER TABLE bookings MODIFY COLUMN booking_type ENUM('flight', 'event', 'package') NOT NULL");
    }
};
