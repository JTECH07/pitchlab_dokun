<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // DOKUN BRIDGE: Messages sémantiques
        Schema::create('dokun_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('artisan_id')->constrained()->cascadeOnDelete();
            $table->string('sender_type'); // 'visitor' or 'artisan'
            $table->text('original_text');
            $table->string('original_language');
            $table->text('translated_text');
            $table->string('translated_language');
            $table->timestamps();
        });

        // DOKUN VOICE: Archives vocales
        Schema::create('dokun_audio_archives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('artisan_id')->constrained()->cascadeOnDelete();
            $table->string('audio_path');
            $table->string('language')->default('fon');
            $table->text('transcription')->nullable();
            $table->text('translation_fr')->nullable();
            $table->integer('duration_seconds')->default(0);
            $table->timestamps();
        });

        // DOKUN LEARN: Mots de vocabulaire contextuels
        Schema::create('dokun_learning_words', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('local_word');
            $table->string('french_translation');
            $table->string('english_translation')->nullable();
            $table->string('language')->default('fon'); // fon, gun, yoruba, etc.
            $table->string('context')->nullable(); // greeting, commerce, craft
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokun_learning_words');
        Schema::dropIfExists('dokun_audio_archives');
        Schema::dropIfExists('dokun_messages');
    }
};
