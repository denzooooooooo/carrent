<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('bookings') || Schema::hasColumn('bookings', 'package_booking_id')) {
            return;
        }

        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('package_booking_id')
                ->nullable()
                ->constrained('package_bookings')
                ->onDelete('set null')
                ->after('event_booking_id');
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('bookings') || !Schema::hasColumn('bookings', 'package_booking_id')) {
            return;
        }

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['package_booking_id']);
            $table->dropColumn('package_booking_id');
        });
    }
};
