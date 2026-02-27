<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->foreignId('event_series_id')->nullable()->constrained('event_series')->onDelete('set null');
            $table->string('match_number')->nullable()->after('event_series_id');
            $table->boolean('is_home_team_match')->default(false)->after('match_number');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['event_series_id']);
            $table->dropColumn(['event_series_id', 'match_number', 'is_home_team_match']);
        });
    }
};

