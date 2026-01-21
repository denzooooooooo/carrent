<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Renommer la colonne amadeus_booking_ref en api_booking_ref
        Schema::table('flight_bookings', function (Blueprint $table) {
            if (Schema::hasColumn('flight_bookings', 'amadeus_booking_ref')) {
                $table->renameColumn('amadeus_booking_ref', 'api_booking_ref');
            }
        });
    }

    public function down(): void
    {
        Schema::table('flight_bookings', function (Blueprint $table) {
            if (Schema::hasColumn('flight_bookings', 'api_booking_ref')) {
                $table->renameColumn('api_booking_ref', 'amadeus_booking_ref');
            }
        });
    }
};

