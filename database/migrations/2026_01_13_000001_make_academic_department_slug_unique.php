<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // 1) Backfill any NULL slugs (if any exist) using a safe fallback.
        // NOTE: This only runs once. You can adjust strategy if you already have slugs everywhere.
        DB::table('academic_departments')
            ->whereNull('slug')
            ->orWhere('slug', '')
            ->orderBy('id')
            ->chunkById(200, function ($rows) {
                foreach ($rows as $row) {
                    $fallback = 'department-' . $row->id;
                    DB::table('academic_departments')->where('id', $row->id)->update(['slug' => $fallback]);
                }
            });

        // 2) Make slug NOT NULL (via change) and add UNIQUE index.
        // MySQL: change() requires doctrine/dbal if your Laravel version needs it.
        Schema::table('academic_departments', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->change();
        });

        // If you already have an index, drop it first (safe attempt).
        // This try/catch prevents failing on fresh installs.
        try {
            Schema::table('academic_departments', function (Blueprint $table) {
                $table->dropIndex(['slug']);
            });
        } catch (\Throwable $e) {
            // ignore
        }

        Schema::table('academic_departments', function (Blueprint $table) {
            $table->unique('slug', 'academic_departments_slug_unique');
        });
    }

    public function down(): void
    {
        // Drop unique index and allow nullable again (revert)
        try {
            Schema::table('academic_departments', function (Blueprint $table) {
                $table->dropUnique('academic_departments_slug_unique');
            });
        } catch (\Throwable $e) {
            // ignore
        }

        Schema::table('academic_departments', function (Blueprint $table) {
            $table->string('slug')->nullable()->change();
        });
    }
};
