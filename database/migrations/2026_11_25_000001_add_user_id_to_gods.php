<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // SQLite сохраняет частичный индекс уникальности имён при прямом ADD COLUMN.
        DB::statement('ALTER TABLE gods ADD COLUMN user_id INTEGER REFERENCES users(id) ON DELETE RESTRICT');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE gods DROP COLUMN user_id');
    }
};
