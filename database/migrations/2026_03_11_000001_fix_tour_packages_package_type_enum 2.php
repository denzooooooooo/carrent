<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('tour_packages') || !Schema::hasColumn('tour_packages', 'package_type')) {
            return;
        }

        DB::statement("ALTER TABLE tour_packages MODIFY COLUMN package_type ENUM(
            'helicopter', 'private_jet', 'cruise', 'safari',
            'city_tour', 'adventure', 'luxury',
            'sport_event', 'motorsport', 'football'
        ) NOT NULL");
    }

    public function down(): void
    {
        if (!Schema::hasTable('tour_packages') || !Schema::hasColumn('tour_packages', 'package_type')) {
            return;
        }

        DB::statement("ALTER TABLE tour_packages MODIFY COLUMN package_type ENUM(
            'helicopter', 'private_jet', 'cruise', 'safari',
            'city_tour', 'adventure', 'luxury'
        ) NOT NULL");
    }
};
