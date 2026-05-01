<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('progresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('module_id')->constrained()->cascadeOnDelete();
            $table->boolean('completed')->default(false);
            $table->integer('stars_earned')->default(0);
            $table->integer('time_spent')->default(0); // seconds
            $table->timestamp('last_activity_at')->nullable();
            $table->timestamps();

            // One progress record per student per module
            $table->unique(['user_id', 'module_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('progresses');
    }
};
