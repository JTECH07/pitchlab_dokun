<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('artisan_applications', function (Blueprint $table) {
            $table->json('selected_savoir_faires')->nullable()->after('trade');
        });
    }

    public function down(): void
    {
        Schema::table('artisan_applications', function (Blueprint $table) {
            $table->dropColumn('selected_savoir_faires');
        });
    }
};
