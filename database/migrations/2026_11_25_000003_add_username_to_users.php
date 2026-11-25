<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // У старых аккаунтов могут совпадать имена. ID даёт каждому стабильный адрес.
        DB::statement("ALTER TABLE users ADD COLUMN username VARCHAR(40) NOT NULL DEFAULT ''");
        DB::statement("UPDATE users SET username = 'user-' || id");
        DB::statement('CREATE UNIQUE INDEX users_username_unique ON users (username)');
    }

    public function down(): void
    {
        DB::statement('DROP INDEX users_username_unique');
        DB::statement('ALTER TABLE users DROP COLUMN username');
    }
};
