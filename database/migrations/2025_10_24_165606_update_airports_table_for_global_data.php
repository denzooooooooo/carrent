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
        Schema::table('airports', function (Blueprint $table) {
            // 🔹 Ajouter les nouvelles colonnes si elles n'existent pas déjà
            if (!Schema::hasColumn('airports', 'ident')) {
                $table->string('ident')->unique()->after('id');
            }
            if (!Schema::hasColumn('airports', 'type')) {
                $table->string('type')->nullable()->after('ident');
            }
            if (!Schema::hasColumn('airports', 'name')) {
                $table->string('name')->nullable()->after('type');
            }
            if (!Schema::hasColumn('airports', 'latitude_deg')) {
                $table->decimal('latitude_deg', 10, 6)->nullable()->after('name');
            }
            if (!Schema::hasColumn('airports', 'longitude_deg')) {
                $table->decimal('longitude_deg', 10, 6)->nullable()->after('latitude_deg');
            }
            if (!Schema::hasColumn('airports', 'elevation_ft')) {
                $table->integer('elevation_ft')->nullable()->after('longitude_deg');
            }
            if (!Schema::hasColumn('airports', 'continent')) {
                $table->string('continent', 5)->nullable()->after('elevation_ft');
            }
            if (!Schema::hasColumn('airports', 'iso_country')) {
                $table->string('iso_country', 5)->after('continent');
            }
            if (!Schema::hasColumn('airports', 'iso_region')) {
                $table->string('iso_region', 10)->nullable()->after('iso_country');
            }
            if (!Schema::hasColumn('airports', 'municipality')) {
                $table->string('municipality')->nullable()->after('iso_region');
            }
            if (!Schema::hasColumn('airports', 'scheduled_service')) {
                $table->string('scheduled_service', 10)->nullable()->after('municipality');
            }
            if (!Schema::hasColumn('airports', 'icao_code')) {
                $table->string('icao_code', 10)->nullable()->after('scheduled_service');
            }
            if (!Schema::hasColumn('airports', 'iata_code')) {
                $table->string('iata_code', 10)->nullable()->after('icao_code');
            }
            if (!Schema::hasColumn('airports', 'gps_code')) {
                $table->string('gps_code', 10)->nullable()->after('iata_code');
            }
            if (!Schema::hasColumn('airports', 'local_code')) {
                $table->string('local_code', 10)->nullable()->after('gps_code');
            }
            if (!Schema::hasColumn('airports', 'home_link')) {
                $table->string('home_link')->nullable()->after('local_code');
            }
            if (!Schema::hasColumn('airports', 'wikipedia_link')) {
                $table->string('wikipedia_link')->nullable()->after('home_link');
            }
            if (!Schema::hasColumn('airports', 'keywords')) {
                $table->text('keywords')->nullable()->after('wikipedia_link');
            }

            // 🔹 Ajouter les clés étrangères
            if (!Schema::hasColumn('airports', 'iso_country')) {
                $table->foreign('iso_country')->references('code')->on('countries')->onDelete('cascade');
            }
            if (!Schema::hasColumn('airports', 'iso_region')) {
                $table->foreign('iso_region')->references('code')->on('regions')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('airports', function (Blueprint $table) {
            // Supprimer les colonnes ajoutées
            $table->dropForeign(['iso_country']);
            $table->dropForeign(['iso_region']);

            $table->dropColumn([
                'ident', 'type', 'name', 'latitude_deg', 'longitude_deg', 'elevation_ft',
                'continent', 'iso_country', 'iso_region', 'municipality', 'scheduled_service',
                'icao_code', 'iata_code', 'gps_code', 'local_code', 'home_link', 'wikipedia_link', 'keywords'
            ]);
        });
    }
};
