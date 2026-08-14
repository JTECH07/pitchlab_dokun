<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('reservation_requests', function (Blueprint $table) {
            $table->foreignId('experience_id')->nullable()->after('artisan_id')->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->after('experience_id')->constrained()->nullOnDelete();
            $table->unsignedInteger('total_amount')->nullable()->after('guests_count');
            $table->string('currency', 3)->default('XOF')->after('total_amount');
            $table->enum('payment_method', ['pay_on_site', 'mobile_money'])->default('pay_on_site')->after('currency');
            $table->enum('payment_status', ['not_required', 'pending', 'paid', 'failed'])->default('not_required')->after('payment_method');
            $table->string('reference')->nullable()->unique()->after('payment_status');
        });
    }
    public function down(): void
    {
        Schema::table('reservation_requests', function (Blueprint $table) {
            $table->dropForeign(['experience_id', 'user_id']);
            $table->dropColumn(['experience_id', 'user_id', 'total_amount', 'currency', 'payment_method', 'payment_status', 'reference']);
        });
    }
};
