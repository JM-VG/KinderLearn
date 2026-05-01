<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('subject', ['alphabet', 'numbers', 'colors', 'shapes', 'words']);
            $table->text('description')->nullable();
            $table->string('icon')->default('📚');     // emoji shown on card
            $table->string('color')->default('#FF6B6B'); // card background color
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->foreignId('teacher_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};
