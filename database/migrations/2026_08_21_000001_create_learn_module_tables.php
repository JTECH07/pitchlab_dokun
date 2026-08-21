<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('learn_courses', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title_fr');
            $table->string('title_en');
            $table->string('desc_fr', 500);
            $table->string('desc_en', 500);
            $table->string('icon', 10)->default('📚');
            $table->string('accent', 20)->default('#064E3B');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('learn_lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('learn_courses')->cascadeOnDelete();
            $table->string('slug');
            $table->string('title_fr');
            $table->string('title_en');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->unique(['course_id', 'slug']);
        });

        Schema::create('learn_words', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained('learn_lessons')->cascadeOnDelete();
            $table->string('local_word');
            $table->string('french_translation');
            $table->string('english_translation');
            $table->string('context', 50)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('learn_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lesson_id')->constrained('learn_lessons')->cascadeOnDelete();
            $table->unsignedTinyInteger('best_score')->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'lesson_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('learn_progress');
        Schema::dropIfExists('learn_words');
        Schema::dropIfExists('learn_lessons');
        Schema::dropIfExists('learn_courses');
    }
};
