<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('experiences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('artisan_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('summary');
            $table->unsignedSmallInteger('duration_minutes')->default(90);
            $table->unsignedSmallInteger('capacity')->default(8);
            $table->unsignedInteger('price')->nullable();
            $table->string('currency', 3)->default('XOF');
            $table->string('language')->default('Français');
            $table->string('image_path')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('experiences'); }
};
