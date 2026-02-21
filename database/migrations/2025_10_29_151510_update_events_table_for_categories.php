<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Supprimer les anciennes colonnes si elles existent
            if (Schema::hasColumn('events', 'event_type')) {
                $table->dropColumn('event_type');
            }

            if (Schema::hasColumn('events', 'sport_type')) {
                $table->dropColumn('sport_type');
            }

            // Ajouter category_id si elle n'existe pas encore
            if (!Schema::hasColumn('events', 'category_id')) {
                $table->foreignId('category_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('event_categories')
                    ->onDelete('set null');
            }

            // Ajouter type_id si elle n'existe pas encore
            if (!Schema::hasColumn('events', 'type_id')) {
                $table->foreignId('type_id')
                    ->nullable()
                    ->after('category_id')
                    ->constrained('event_types')
                    ->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Supprimer type_id si présent
            if (Schema::hasColumn('events', 'type_id')) {
                $table->dropConstrainedForeignId('type_id');
            }

            // Supprimer category_id si présent
            if (Schema::hasColumn('events', 'category_id')) {
                $table->dropConstrainedForeignId('category_id');
            }

            // Restaurer les anciennes colonnes
            if (!Schema::hasColumn('events', 'event_type')) {
                $table->enum('event_type', ['sport', 'concert', 'theater', 'festival', 'other'])->nullable();
            }

            if (!Schema::hasColumn('events', 'sport_type')) {
                $table->string('sport_type', 100)->nullable();
            }
        });
    }
};
