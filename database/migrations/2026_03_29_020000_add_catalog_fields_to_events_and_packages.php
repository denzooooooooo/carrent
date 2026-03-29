<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('events')) {
            Schema::table('events', function (Blueprint $table) {
                if (!Schema::hasColumn('events', 'tagline_fr')) {
                    $table->string('tagline_fr')->nullable()->after('title_en');
                }

                if (!Schema::hasColumn('events', 'tagline_en')) {
                    $table->string('tagline_en')->nullable()->after('tagline_fr');
                }

                if (!Schema::hasColumn('events', 'program_fr')) {
                    $table->longText('program_fr')->nullable()->after('description_en');
                }

                if (!Schema::hasColumn('events', 'program_en')) {
                    $table->longText('program_en')->nullable()->after('program_fr');
                }

                if (!Schema::hasColumn('events', 'conditions_fr')) {
                    $table->longText('conditions_fr')->nullable()->after('program_en');
                }

                if (!Schema::hasColumn('events', 'conditions_en')) {
                    $table->longText('conditions_en')->nullable()->after('conditions_fr');
                }

                if (!Schema::hasColumn('events', 'source_catalog')) {
                    $table->string('source_catalog')->nullable()->after('conditions_en');
                }
            });
        }

        if (Schema::hasTable('event_packages')) {
            Schema::table('event_packages', function (Blueprint $table) {
                if (!Schema::hasColumn('event_packages', 'venue_details_fr')) {
                    $table->text('venue_details_fr')->nullable()->after('description_fr');
                }

                if (!Schema::hasColumn('event_packages', 'venue_details_en')) {
                    $table->text('venue_details_en')->nullable()->after('venue_details_fr');
                }

                if (!Schema::hasColumn('event_packages', 'minimum_quantity')) {
                    $table->unsignedInteger('minimum_quantity')->default(1)->after('currency');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('event_packages')) {
            Schema::table('event_packages', function (Blueprint $table) {
                $columns = array_filter([
                    Schema::hasColumn('event_packages', 'venue_details_fr') ? 'venue_details_fr' : null,
                    Schema::hasColumn('event_packages', 'venue_details_en') ? 'venue_details_en' : null,
                    Schema::hasColumn('event_packages', 'minimum_quantity') ? 'minimum_quantity' : null,
                ]);

                if ($columns !== []) {
                    $table->dropColumn($columns);
                }
            });
        }

        if (Schema::hasTable('events')) {
            Schema::table('events', function (Blueprint $table) {
                $columns = array_filter([
                    Schema::hasColumn('events', 'tagline_fr') ? 'tagline_fr' : null,
                    Schema::hasColumn('events', 'tagline_en') ? 'tagline_en' : null,
                    Schema::hasColumn('events', 'program_fr') ? 'program_fr' : null,
                    Schema::hasColumn('events', 'program_en') ? 'program_en' : null,
                    Schema::hasColumn('events', 'conditions_fr') ? 'conditions_fr' : null,
                    Schema::hasColumn('events', 'conditions_en') ? 'conditions_en' : null,
                    Schema::hasColumn('events', 'source_catalog') ? 'source_catalog' : null,
                ]);

                if ($columns !== []) {
                    $table->dropColumn($columns);
                }
            });
        }
    }
};
