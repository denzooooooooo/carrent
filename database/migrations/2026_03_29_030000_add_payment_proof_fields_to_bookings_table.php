<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'payment_proof_path')) {
                $table->string('payment_proof_path')->nullable()->after('receipt_pdf_path');
            }

            if (!Schema::hasColumn('bookings', 'payment_proof_uploaded_at')) {
                $table->timestamp('payment_proof_uploaded_at')->nullable()->after('payment_proof_path');
            }

            if (!Schema::hasColumn('bookings', 'payment_proof_notes')) {
                $table->text('payment_proof_notes')->nullable()->after('payment_proof_uploaded_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $columns = array_filter([
                Schema::hasColumn('bookings', 'payment_proof_path') ? 'payment_proof_path' : null,
                Schema::hasColumn('bookings', 'payment_proof_uploaded_at') ? 'payment_proof_uploaded_at' : null,
                Schema::hasColumn('bookings', 'payment_proof_notes') ? 'payment_proof_notes' : null,
            ]);

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};
