<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'payment_method')) {
                $table->string('payment_method', 50)->nullable()->after('payment_transaction_id');
            }

            if (!Schema::hasColumn('bookings', 'notes')) {
                $table->text('notes')->nullable()->after('special_requests');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $columns = [];

            if (Schema::hasColumn('bookings', 'payment_method')) {
                $columns[] = 'payment_method';
            }

            if (Schema::hasColumn('bookings', 'notes')) {
                $columns[] = 'notes';
            }

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};
