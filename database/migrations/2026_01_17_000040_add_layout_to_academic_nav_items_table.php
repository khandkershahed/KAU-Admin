<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('academic_nav_items', function (Blueprint $table) {
            if (!Schema::hasColumn('academic_nav_items', 'layout')) {
                $table->enum('layout', ['dropdown', 'mega'])->nullable()->after('menu_location');
            }
        });
    }

    public function down(): void
    {
        Schema::table('academic_nav_items', function (Blueprint $table) {
            if (Schema::hasColumn('academic_nav_items', 'layout')) {
                $table->dropColumn('layout');
            }
        });
    }
};
