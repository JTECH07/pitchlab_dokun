<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('reservation_requests') || !Schema::hasColumn('reservation_requests', 'kkiapay_transaction_id')) {
            return;
        }

        Schema::table('reservation_requests', function (Blueprint $table) {
            $table->dropUnique(['kkiapay_transaction_id']);
        });

        Schema::table('reservation_requests', function (Blueprint $table) {
            $table->dropColumn('kkiapay_transaction_id');
        });
    }

    public function down(): void
    {
        Schema::table('reservation_requests', function (Blueprint $table) {
            $table->string('kkiapay_transaction_id')->nullable()->unique()->after('reference');
        });
    }
};
