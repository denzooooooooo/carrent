<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tour_packages', function (Blueprint $table) {
            $table->string('currency', 10)->default('XOF')->after('price');
            $table->date('event_date_start')->nullable()->after('currency');
            $table->date('event_date_end')->nullable()->after('event_date_start');
        });
    }

    public function down(): void
    {
        Schema::table('tour_packages', function (Blueprint $table) {
            $table->dropColumn(['currency', 'event_date_start', 'event_date_end']);
        });
    }
};
