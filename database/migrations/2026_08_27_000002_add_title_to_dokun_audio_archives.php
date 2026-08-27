<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dokun_audio_archives', function (Blueprint $table) {
            if (!Schema::hasColumn('dokun_audio_archives', 'title')) {
                $table->string('title', 120)->nullable()->after('language');
            }
        });
    }

    public function down(): void
    {
        Schema::table('dokun_audio_archives', function (Blueprint $table) {
            $table->dropColumn('title');
        });
    }
};
