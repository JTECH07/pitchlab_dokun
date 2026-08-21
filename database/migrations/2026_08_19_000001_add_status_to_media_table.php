<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('media', function (Blueprint $table) {
            $table->enum('status', ['pending', 'published', 'rejected'])->default('pending')->after('type');
            $table->foreignId('moderated_by')->nullable()->after('status');
            $table->timestamp('moderated_at')->nullable()->after('moderated_by');
        });
    }

    public function down(): void
    {
        Schema::table('media', function (Blueprint $table) {
            $table->dropColumn(['status', 'moderated_by', 'moderated_at']);
        });
    }
};
