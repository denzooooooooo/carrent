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
        Schema::table('events', function (Blueprint $table) {
            if (!Schema::hasColumn('events', 'cost_price')) {
                $table->decimal('cost_price', 10, 2)->nullable()->after('max_price')->comment('Prix d\'achat/coût');
            }
            if (!Schema::hasColumn('events', 'profit_margin')) {
                $table->decimal('profit_margin', 5, 2)->nullable()->after('cost_price')->comment('Marge bénéficiaire en %');
            }
            if (!Schema::hasColumn('events', 'commission_rate')) {
                $table->decimal('commission_rate', 5, 2)->default(15.00)->after('profit_margin')->comment('Taux de commission en %');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['cost_price', 'profit_margin', 'commission_rate']);
        });
    }
};
