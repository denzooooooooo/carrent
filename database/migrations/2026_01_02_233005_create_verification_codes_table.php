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
        Schema::create('verification_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('code', 6); // Code à 6 chiffres
            $table->enum('type', ['email', 'sms']); // Type de vérification
            $table->string('contact'); // Email ou numéro de téléphone
            $table->timestamp('expires_at'); // Date d'expiration (15 minutes)
            $table->boolean('is_used')->default(false); // Code déjà utilisé?
            $table->timestamp('used_at')->nullable(); // Quand le code a été utilisé
            $table->integer('attempts')->default(0); // Nombre de tentatives
            $table->timestamps();
            
            // Index pour performance
            $table->index(['user_id', 'type', 'is_used']);
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verification_codes');
    }
};
