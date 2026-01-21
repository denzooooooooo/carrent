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
        Schema::table('flight_bookings', function (Blueprint $table) {
            // Vérifier et ajouter les colonnes manquantes
            if (!Schema::hasColumn('flight_bookings', 'flight_number')) {
                $table->string('flight_number')->nullable()->after('booking_id');
            }
            if (!Schema::hasColumn('flight_bookings', 'airline')) {
                $table->string('airline')->nullable()->after('flight_number');
            }
            if (!Schema::hasColumn('flight_bookings', 'departure_airport')) {
                $table->string('departure_airport', 10)->nullable()->after('airline');
            }
            if (!Schema::hasColumn('flight_bookings', 'arrival_airport')) {
                $table->string('arrival_airport', 10)->nullable()->after('departure_airport');
            }
            if (!Schema::hasColumn('flight_bookings', 'departure_time')) {
                $table->dateTime('departure_time')->nullable()->after('arrival_airport');
            }
            if (!Schema::hasColumn('flight_bookings', 'arrival_time')) {
                $table->dateTime('arrival_time')->nullable()->after('departure_time');
            }
            if (!Schema::hasColumn('flight_bookings', 'departure_date')) {
                $table->date('departure_date')->nullable()->after('arrival_time');
            }
            if (!Schema::hasColumn('flight_bookings', 'cabin_class')) {
                $table->string('cabin_class', 20)->nullable()->after('departure_date');
            }
            if (!Schema::hasColumn('flight_bookings', 'duffel_offer_id')) {
                $table->string('duffel_offer_id')->nullable()->after('cabin_class');
            }
            if (!Schema::hasColumn('flight_bookings', 'duffel_order_id')) {
                $table->string('duffel_order_id')->nullable()->after('duffel_offer_id');
            }
            if (!Schema::hasColumn('flight_bookings', 'duffel_booking_reference')) {
                $table->string('duffel_booking_reference', 100)->nullable()->after('duffel_order_id');
            }
            if (!Schema::hasColumn('flight_bookings', 'base_price')) {
                $table->decimal('base_price', 10, 2)->nullable()->after('duffel_offer_id');
            }
            if (!Schema::hasColumn('flight_bookings', 'total_price')) {
                $table->decimal('total_price', 10, 2)->nullable()->after('base_price');
            }
            if (!Schema::hasColumn('flight_bookings', 'passengers_count')) {
                $table->integer('passengers_count')->default(1)->after('total_price');
            }
            if (!Schema::hasColumn('flight_bookings', 'adults_count')) {
                $table->integer('adults_count')->default(1)->after('passengers_count');
            }
            if (!Schema::hasColumn('flight_bookings', 'children_count')) {
                $table->integer('children_count')->default(0)->after('adults_count');
            }
            if (!Schema::hasColumn('flight_bookings', 'infants_count')) {
                $table->integer('infants_count')->default(0)->after('children_count');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('flight_bookings', function (Blueprint $table) {
            $columns = [
                'flight_number', 'airline', 'departure_airport', 'arrival_airport',
                'departure_time', 'arrival_time', 'departure_date', 'cabin_class',
                'duffel_offer_id', 'base_price', 'total_price', 'passengers_count',
                'adults_count', 'children_count', 'infants_count'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('flight_bookings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
