<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // --- Achievements table ---
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('icon')->default('🏆');
            $table->enum('type', ['badge', 'milestone', 'perfect_score'])->default('badge');
            $table->timestamp('earned_at')->nullable();
            $table->timestamps();
        });

        // --- Messages table ---
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('receiver_id')->constrained('users')->cascadeOnDelete();
            $table->string('subject');
            $table->text('body');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        // --- Announcements table ---
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('section_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->text('body');
            $table->boolean('pinned')->default(false);
            $table->timestamps();
        });

        // --- Attendance table ---
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('section_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->enum('status', ['present', 'absent', 'late'])->default('present');
            $table->text('notes')->nullable();
            $table->timestamps();

            // Only one record per student per day
            $table->unique(['user_id', 'date']);
        });

        // --- User notifications table ---
        Schema::create('user_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('message');
            $table->enum('type', ['achievement', 'message', 'announcement', 'activity'])->default('activity');
            $table->string('link')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_notifications');
        Schema::dropIfExists('attendances');
        Schema::dropIfExists('announcements');
        Schema::dropIfExists('messages');
        Schema::dropIfExists('achievements');
    }
};
