<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('academic_pages', function (Blueprint $table) {
            if (!Schema::hasColumn('academic_pages', 'owner_type')) {
                $table->string('owner_type')->default('site')->after('nav_item_id'); // main|site|department
            }
            if (!Schema::hasColumn('academic_pages', 'owner_id')) {
                $table->unsignedBigInteger('owner_id')->nullable()->after('owner_type'); // null for main
            }
            if (!Schema::hasColumn('academic_pages', 'template_key')) {
                $table->string('template_key')->default('default')->after('title');
            }
            if (!Schema::hasColumn('academic_pages', 'settings')) {
                $table->json('settings')->nullable()->after('content'); // per-page flags
            }
        });

        // Backfill: existing site pages
        DB::table('academic_pages')
            ->where('owner_type', 'site')
            ->whereNull('owner_id')
            ->whereNotNull('academic_site_id')
            ->update([
                'owner_id' => DB::raw('academic_site_id'),
            ]);

        // Drop old unique (academic_site_id, slug)
        try {
            Schema::table('academic_pages', function (Blueprint $table) {
                $table->dropUnique('academic_pages_academic_site_id_slug_unique');
            });
        } catch (\Throwable $e) {}

        // New unique: (owner_type, owner_id, slug)
        try {
            Schema::table('academic_pages', function (Blueprint $table) {
                $table->unique(['owner_type','owner_id','slug'], 'academic_pages_owner_slug_unique');
            });
        } catch (\Throwable $e) {}
    }

    public function down(): void
    {
        try {
            Schema::table('academic_pages', function (Blueprint $table) {
                $table->dropUnique('academic_pages_owner_slug_unique');
            });
        } catch (\Throwable $e) {}

        Schema::table('academic_pages', function (Blueprint $table) {
            if (Schema::hasColumn('academic_pages', 'settings')) $table->dropColumn('settings');
            if (Schema::hasColumn('academic_pages', 'template_key')) $table->dropColumn('template_key');
            if (Schema::hasColumn('academic_pages', 'owner_id')) $table->dropColumn('owner_id');
            if (Schema::hasColumn('academic_pages', 'owner_type')) $table->dropColumn('owner_type');
        });

        // Restore old unique
        try {
            Schema::table('academic_pages', function (Blueprint $table) {
                $table->unique(['academic_site_id','slug']);
            });
        } catch (\Throwable $e) {}
    }
};
