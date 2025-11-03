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
        Schema::table('event_seat_zones', function (Blueprint $table) {
            $table->enum('zone_type', ['standard', 'vip', 'vvip', 'premium'])->default('standard')->after('zone_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_seat_zones', function (Blueprint $table) {
            $table->dropColumn('zone_type');
        });
    }
};
