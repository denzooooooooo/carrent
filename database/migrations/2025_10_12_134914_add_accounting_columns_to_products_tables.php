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
        // Ajouter colonnes comptables à la table flights
        Schema::table('flights', function (Blueprint $table) {
            $table->decimal('cost_price_economy', 10, 2)->nullable()->after('economy_price')->comment('Prix d\'achat Economy');
            $table->decimal('cost_price_business', 10, 2)->nullable()->after('business_price')->comment('Prix d\'achat Business');
            $table->decimal('cost_price_first_class', 10, 2)->nullable()->after('first_class_price')->comment('Prix d\'achat First Class');
            $table->decimal('profit_margin_economy', 5, 2)->nullable()->comment('Marge bénéficiaire Economy en %');
            $table->decimal('profit_margin_business', 5, 2)->nullable()->comment('Marge bénéficiaire Business en %');
            $table->decimal('profit_margin_first_class', 5, 2)->nullable()->comment('Marge bénéficiaire First Class en %');
            $table->decimal('commission_rate', 5, 2)->default(10.00)->comment('Taux de commission en %');
        });

        // Ajouter colonnes comptables à la table events
        Schema::table('events', function (Blueprint $table) {
            $table->decimal('cost_price', 10, 2)->nullable()->after('max_price')->comment('Prix d\'achat/coût');
            $table->decimal('profit_margin', 5, 2)->nullable()->comment('Marge bénéficiaire en %');
            $table->decimal('commission_rate', 5, 2)->default(15.00)->comment('Taux de commission en %');
        });

        // Ajouter colonnes comptables à la table event_seat_zones
        Schema::table('event_seat_zones', function (Blueprint $table) {
            $table->decimal('cost_price', 10, 2)->nullable()->after('price')->comment('Prix d\'achat par siège');
            $table->decimal('profit_margin', 5, 2)->nullable()->comment('Marge bénéficiaire en %');
        });

        // Ajouter colonnes comptables à la table tour_packages
        Schema::table('tour_packages', function (Blueprint $table) {
            $table->decimal('cost_price', 10, 2)->nullable()->after('discount_price')->comment('Prix d\'achat/coût');
            $table->decimal('profit_margin', 5, 2)->nullable()->comment('Marge bénéficiaire en %');
            $table->decimal('commission_rate', 5, 2)->default(20.00)->comment('Taux de commission en %');
            $table->decimal('supplier_cost', 10, 2)->nullable()->comment('Coût fournisseur');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('flights', function (Blueprint $table) {
            $table->dropColumn([
                'cost_price_economy',
                'cost_price_business',
                'cost_price_first_class',
                'profit_margin_economy',
                'profit_margin_business',
                'profit_margin_first_class',
                'commission_rate'
            ]);
        });

        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['cost_price', 'profit_margin', 'commission_rate']);
        });

        Schema::table('event_seat_zones', function (Blueprint $table) {
            $table->dropColumn(['cost_price', 'profit_margin']);
        });

        Schema::table('tour_packages', function (Blueprint $table) {
            $table->dropColumn(['cost_price', 'profit_margin', 'commission_rate', 'supplier_cost']);
        });
    }
};
