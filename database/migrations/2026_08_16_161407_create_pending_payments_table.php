<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pending_payments', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->json('reservation_data');
            $table->string('fedapay_transaction_id')->nullable()->index();
            $table->enum('status', ['pending', 'completed', 'expired', 'failed'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pending_payments');
    }
};
