<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('artisan_savoir_faire', function (Blueprint $table) {
            $table->foreignId('artisan_id')->constrained()->onDelete('cascade');
            $table->foreignId('savoir_faire_id')->constrained()->onDelete('cascade');
            $table->primary(['artisan_id', 'savoir_faire_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artisan_savoir_faire');
    }
};
