<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Table pour stocker les commandes Duffel v2
     * Inclut les slices, conditions, et customer users
     */
    public function up(): void
    {
        Schema::create('duffel_orders', function (Blueprint $table) {
            $table->id();
            
            // Duffel IDs
            $table->string('duffel_order_id')->unique();
            $table->string('booking_reference')->nullable(); // Référence PNR
            
            // Status
            $table->string('status'); // pending, confirmed, cancelled, pending_payment
            
            // Offer
            $table->string('offer_id')->nullable();
            
            // Prix détaillé (v2)
            $table->decimal('base_amount', 10, 2);
            $table->decimal('tax_amount', 10, 2);
            $table->decimal('total_amount', 10, 2);
            $table->string('currency', 3)->default('XOF');
            
            // Commission (v2 - par classe)
            $table->decimal('commission_amount', 10, 2);
            $table->decimal('commission_rate', 5, 4);
            $table->string('cabin_class'); // economy, business, first
            
            // Données JSON complètes (v2)
            $table->json('passengers'); // Tableau complet des passagers
            $table->json('slices'); // Itinéraire complet avec segments
            $table->json('conditions'); // Conditions de modif/remboursement v2
            $table->json('raw_order')->nullable(); // Réponse complète de l'API
            
            // Customer Users (v2)
            $table->json('customer_user_ids')->nullable();
            
            // Payment
            $table->string('payment_intent_id')->nullable();
            $table->string('payment_status')->nullable(); // requires_payment_method, requires_confirmation, etc.
            
            // Timestamps
            $table->timestamp('offer_expires_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamp('refunded_at')->nullable();
            
            // Liens
            $table->foreignId('booking_id')->nullable()->constrained('bookings')->onDelete('set null');
            
            $table->timestamps();
            
            // Index
            $table->index('status');
            $table->index('booking_reference');
            $table->index('cabin_class');
            $table->index(['booking_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('duffel_orders');
    }
};

