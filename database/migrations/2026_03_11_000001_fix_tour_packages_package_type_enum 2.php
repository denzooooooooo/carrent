<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE tour_packages MODIFY COLUMN package_type ENUM(
            'helicopter', 'private_jet', 'cruise', 'safari',
            'city_tour', 'adventure', 'luxury',
            'sport_event', 'motorsport', 'football'
        ) NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE tour_packages MODIFY COLUMN package_type ENUM(
            'helicopter', 'private_jet', 'cruise', 'safari',
            'city_tour', 'adventure', 'luxury'
        ) NOT NULL");
    }
};
