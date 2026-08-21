<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservation_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('reservation_requests', 'fedapay_transaction_id')) {
                $table->string('fedapay_transaction_id')->nullable()->after('kkiapay_transaction_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('reservation_requests', function (Blueprint $table) {
            $table->dropColumn('fedapay_transaction_id');
        });
    }
};
