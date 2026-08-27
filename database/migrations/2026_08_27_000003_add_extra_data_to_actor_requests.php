<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('actor_requests', function (Blueprint $table) {
            $table->json('extra_data')->nullable()->after('motivation');
        });
    }

    public function down(): void
    {
        Schema::table('actor_requests', function (Blueprint $table) {
            $table->dropColumn('extra_data');
        });
    }
};
