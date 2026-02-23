<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1) Make title TEXT (avoid truncation)
        DB::statement("
            ALTER TABLE academic_member_publications
            MODIFY title TEXT NOT NULL
        ");

        // 2) Add category column (for International/National/Book etc.)
        DB::statement("
            ALTER TABLE academic_member_publications
            ADD COLUMN category VARCHAR(150) NULL AFTER type
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE academic_member_publications
            MODIFY title VARCHAR(255) NOT NULL
        ");

        DB::statement("
            ALTER TABLE academic_member_publications
            DROP COLUMN category
        ");
    }
};
