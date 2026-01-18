<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('notices', function (Blueprint $table) {
            $table->string('employee_name')->nullable()->after('publish_date');
            $table->string('designation')->nullable()->after('employee_name');
            $table->string('department')->nullable()->after('designation');
        });
    }

    public function down(): void
    {
        Schema::table('notices', function (Blueprint $table) {
            $table->dropColumn(['employee_name', 'designation', 'department']);
        });
    }
};
