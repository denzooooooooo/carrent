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
        Schema::table('bookings', function (Blueprint $table) {
            // Vérifier que la colonne n'existe pas avant de l'ajouter
            if (!Schema::hasColumn('bookings', 'location_id')) {
                $table->foreignId('location_id')
                    ->nullable()
                    ->after('package_id')
                    ->constrained('locations')
                    ->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {

            // Supprimer la contrainte uniquement si elle existe
            if (Schema::hasColumn('bookings', 'location_id')) {

                // Vérifier si la clé étrangère existe
                $sm = Schema::getConnection()->getDoctrineSchemaManager();
                $doctrineTable = $sm->listTableDetails('bookings');

                if ($doctrineTable->hasForeignKey('bookings_location_id_foreign')) {
                    $table->dropForeign('bookings_location_id_foreign');
                }

                // Supprimer ensuite la colonne
                $table->dropColumn('location_id');
            }
        });
    }
};
