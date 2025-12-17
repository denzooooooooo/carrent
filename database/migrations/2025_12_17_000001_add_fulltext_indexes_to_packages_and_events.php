<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds FULLTEXT indexes to improve search performance (MySQL/MariaDB).
     * Note: Make a DB backup before running on production.
     */
    public function up(): void
    {
        // Tour packages: add fulltext on title_fr and description_fr
        try {
            DB::statement('ALTER TABLE `tour_packages` ADD FULLTEXT idx_ft_title_description (title_fr, description_fr)');
        } catch (\Throwable $e) {
            // ignore if index exists or DB engine doesn't support FULLTEXT
        }

        // Events: add fulltext on title_fr and description_fr
        try {
            DB::statement('ALTER TABLE `events` ADD FULLTEXT idx_ft_event_title_description (title_fr, description_fr)');
        } catch (\Throwable $e) {
            // ignore errors
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            DB::statement('ALTER TABLE `tour_packages` DROP INDEX idx_ft_title_description');
        } catch (\Throwable $e) {
            // ignore
        }

        try {
            DB::statement('ALTER TABLE `events` DROP INDEX idx_ft_event_title_description');
        } catch (\Throwable $e) {
            // ignore
        }
    }
};
