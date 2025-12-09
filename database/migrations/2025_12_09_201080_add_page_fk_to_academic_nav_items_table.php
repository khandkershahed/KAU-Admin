<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('academic_nav_items', function (Blueprint $table) {
            $table->foreign('page_id')
                  ->references('id')
                  ->on('academic_pages')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('academic_nav_items', function (Blueprint $table) {
            $table->dropForeign(['page_id']);
        });
    }
};
