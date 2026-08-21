<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('artisans', function (Blueprint $table) {
            $table->string('photo_path')->nullable()->after('last_name');
            $table->string('pending_photo_path')->nullable()->after('photo_path');
        });
    }

    public function down(): void
    {
        Schema::table('artisans', function (Blueprint $table) {
            $table->dropColumn(['photo_path', 'pending_photo_path']);
        });
    }
};
