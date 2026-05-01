<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->enum('type', ['video', 'quiz', 'matching', 'drag_drop', 'coloring', 'audio']);
            $table->json('content')->nullable();    // quiz questions, matching pairs, etc.
            $table->string('file_path')->nullable(); // uploaded PDF, video, audio
            $table->timestamp('deadline')->nullable();
            $table->integer('stars_reward')->default(3);
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
