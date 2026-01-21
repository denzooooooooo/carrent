<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Table pour stocker les modifications et annulations de commandes Duffel v2
     * Gère les change quotes, cancellations, et Airline Initiated Changes
     */
    public function up(): void
    {
        Schema::create('duffel_order_changes', function (Blueprint $table) {
            $table->id();
            
            // Relations
            $table->foreignId('duffel_order_id')->constrained('duffel_orders')->onDelete('cascade');
            
            // Type de changement
            $table->string('type'); // modification, cancellation, airline_initiated_change
            
            // Duffel IDs
            $table->string('change_quote_id')->nullable();
            $table->string('cancellation_id')->nullable();
            $table->string('change_request_id')->nullable();
            $table->string('new_order_id')->nullable(); // Pour les modifications confirmées
            
            // Statut
            $table->string('status'); // pending, confirmed, rejected, completed
            
            // Montants (v2)
            $table->decimal('penalty_amount', 10, 2)->nullable();
            $table->string('penalty_currency', 3)->nullable()->default('XOF');
            $table->decimal('additional_payment_amount', 10, 2)->nullable();
            $table->string('additional_payment_currency', 3)->nullable()->default('XOF');
            $table->decimal('refund_amount', 10, 2)->nullable();
            $table->string('refund_currency', 3)->nullable()->default('XOF');
            
            // Nouveaux détails (pour modifications)
            $table->json('new_slices')->nullable(); // Nouvel itinéraire si modification
            $table->decimal('new_total_amount', 10, 2)->nullable();
            $table->string('new_currency', 3)->nullable()->default('XOF');
            
            // Conditions appliquées
            $table->json('applied_conditions')->nullable(); // Conditions utilisées pour le calcul
            
            // Raisons (pour Airline Initiated Changes)
            $table->string('change_reason')->nullable(); // airline_schedule_change, etc.
            $table->text('customer_notification')->nullable(); // Message envoyé au client
            
            // Timestamps
            $table->timestamp('requested_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            
            $table->timestamps();
            
            // Index
            $table->index('type');
            $table->index('status');
            $table->index(['duffel_order_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('duffel_order_changes');
    }
};

