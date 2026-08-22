<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Journal de tous les points gagnés (ledger — source de vérité)
        Schema::create('loyalty_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('code', 50);           // lesson_completed, review_published...
            $table->unsignedInteger('points');
            $table->json('meta')->nullable();     // {lesson_id, score, ...}
            $table->timestamps();
            $table->index(['user_id', 'code']);
        });

        // Badges définis par la plateforme
        Schema::create('badges', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name_fr', 80);
            $table->string('name_en', 80);
            $table->string('desc_fr', 200);
            $table->string('desc_en', 200);
            $table->string('icon', 40);           // clé du composant x-icon
            $table->timestamps();
        });

        Schema::create('badge_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('badge_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamp('earned_at');
            $table->unique(['badge_id', 'user_id']);
        });

        // Streaks quotidiens + agrégats rapides (évite les SUM à chaque page)
        Schema::create('loyalty_summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->unsignedInteger('total_points')->default(0);
            $table->unsignedSmallInteger('streak_days')->default(0);
            $table->date('last_activity_date')->nullable();
            $table->timestamp('streak_updated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loyalty_summaries');
        Schema::dropIfExists('badge_user');
        Schema::dropIfExists('badges');
        Schema::dropIfExists('loyalty_events');
    }
};
