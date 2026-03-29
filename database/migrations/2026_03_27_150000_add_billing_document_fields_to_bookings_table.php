<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'invoice_number')) {
                $table->string('invoice_number')->nullable()->after('notes');
            }

            if (!Schema::hasColumn('bookings', 'invoice_pdf_path')) {
                $table->string('invoice_pdf_path')->nullable()->after('invoice_number');
            }

            if (!Schema::hasColumn('bookings', 'receipt_number')) {
                $table->string('receipt_number')->nullable()->after('invoice_pdf_path');
            }

            if (!Schema::hasColumn('bookings', 'receipt_pdf_path')) {
                $table->string('receipt_pdf_path')->nullable()->after('receipt_number');
            }

            if (!Schema::hasColumn('bookings', 'documents_generated_at')) {
                $table->timestamp('documents_generated_at')->nullable()->after('receipt_pdf_path');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $columns = array_filter([
                Schema::hasColumn('bookings', 'invoice_number') ? 'invoice_number' : null,
                Schema::hasColumn('bookings', 'invoice_pdf_path') ? 'invoice_pdf_path' : null,
                Schema::hasColumn('bookings', 'receipt_number') ? 'receipt_number' : null,
                Schema::hasColumn('bookings', 'receipt_pdf_path') ? 'receipt_pdf_path' : null,
                Schema::hasColumn('bookings', 'documents_generated_at') ? 'documents_generated_at' : null,
            ]);

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};
