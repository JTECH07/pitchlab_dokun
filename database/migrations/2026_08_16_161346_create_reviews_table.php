<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('artisan_id')->constrained()->onDelete('cascade');
            $table->foreignId('reservation_request_id')->nullable()->constrained('reservation_requests')->onDelete('set null');
            $table->unsignedTinyInteger('rating');               // 1-5
            $table->text('comment');
            $table->enum('status', ['pending', 'published', 'rejected'])->default('pending');
            $table->foreignId('moderated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('moderated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
