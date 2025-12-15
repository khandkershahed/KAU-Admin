<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('academic_staff_members', function (Blueprint $table) {
            if (!Schema::hasColumn('academic_staff_members', 'uuid')) {
                $table->string('uuid', 8)->unique()->nullable()->after('position');
            }

            if (!Schema::hasColumn('academic_staff_members', 'mobile')) {
                $table->string('mobile', 20)->nullable()->after('phone');
            }

            if (!Schema::hasColumn('academic_staff_members', 'address')) {
                $table->text('address')->nullable()->after('mobile');
            }

            if (!Schema::hasColumn('academic_staff_members', 'research_interest')) {
                $table->text('research_interest')->nullable()->after('address');
            }

            if (!Schema::hasColumn('academic_staff_members', 'bio')) {
                $table->longText('bio')->nullable()->after('research_interest');
            }

            if (!Schema::hasColumn('academic_staff_members', 'education')) {
                $table->longText('education')->nullable()->after('bio');
            }

            if (!Schema::hasColumn('academic_staff_members', 'experience')) {
                $table->longText('experience')->nullable()->after('education');
            }

            if (!Schema::hasColumn('academic_staff_members', 'scholarship')) {
                $table->longText('scholarship')->nullable()->after('experience');
            }

            if (!Schema::hasColumn('academic_staff_members', 'research')) {
                $table->longText('research')->nullable()->after('scholarship');
            }

            if (!Schema::hasColumn('academic_staff_members', 'teaching')) {
                $table->longText('teaching')->nullable()->after('research');
            }
        });
    }

    public function down(): void
    {
        Schema::table('academic_staff_members', function (Blueprint $table) {
            $drop = [];
            foreach ([
                'uuid','mobile','address','research_interest','bio','education',
                'experience','scholarship','research','teaching'
            ] as $col) {
                if (Schema::hasColumn('academic_staff_members', $col)) {
                    $drop[] = $col;
                }
            }
            if (!empty($drop)) {
                $table->dropColumn($drop);
            }
        });
    }
};
