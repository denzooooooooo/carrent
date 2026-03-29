<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('event_bookings') || Schema::hasColumn('event_bookings', 'package_option_id')) {
            return;
        }

        Schema::table('event_bookings', function (Blueprint $table) {
            $table
                ->foreignId('package_option_id')
                ->nullable()
                ->after('package_id')
                ->constrained('event_package_options')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('event_bookings') || !Schema::hasColumn('event_bookings', 'package_option_id')) {
            return;
        }

        Schema::table('event_bookings', function (Blueprint $table) {
            $table->dropForeign(['package_option_id']);
            $table->dropColumn('package_option_id');
        });
    }
};
