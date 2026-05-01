<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE activities MODIFY COLUMN type ENUM('video','quiz','matching','drag_drop','coloring','audio','tracing') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE activities MODIFY COLUMN type ENUM('video','quiz','matching','drag_drop','coloring','audio') NOT NULL");
    }
};
