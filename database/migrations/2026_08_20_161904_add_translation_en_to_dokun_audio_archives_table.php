<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dokun_audio_archives', function (Blueprint $table) {
            $table->text('translation_en')->nullable()->after('translation_fr');
        });
    }

    public function down(): void
    {
        Schema::table('dokun_audio_archives', function (Blueprint $table) {
            $table->dropColumn('translation_en');
        });
    }
};
