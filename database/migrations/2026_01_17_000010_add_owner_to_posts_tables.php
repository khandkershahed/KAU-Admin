<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->addOwnerColumns('notices');
        $this->addOwnerColumns('news');
        $this->addOwnerColumns('events');
        $this->addOwnerColumns('tenders');
    }

    public function down(): void
    {
        $this->dropOwnerColumns('notices');
        $this->dropOwnerColumns('news');
        $this->dropOwnerColumns('events');
        $this->dropOwnerColumns('tenders');
    }

    private function addOwnerColumns(string $tableName): void
    {
        Schema::table($tableName, function (Blueprint $table) use ($tableName) {
            if (!Schema::hasColumn($tableName, 'owner_type')) {
                $table->string('owner_type', 50)->default('main')->after('id');
            }

            if (!Schema::hasColumn($tableName, 'owner_id')) {
                $table->unsignedBigInteger('owner_id')->nullable()->after('owner_type');
            }

            // Indexes (safe to attempt only if the referenced columns exist)
            if (Schema::hasColumn($tableName, 'status')) {
                $indexName = $tableName . '_owner_status_idx';
                $table->index(['owner_type', 'owner_id', 'status'], $indexName);
            } else {
                $indexName = $tableName . '_owner_idx';
                $table->index(['owner_type', 'owner_id'], $indexName);
            }
        });
    }

    private function dropOwnerColumns(string $tableName): void
    {
        Schema::table($tableName, function (Blueprint $table) use ($tableName) {
            // Drop indexes if they exist
            $idx1 = $tableName . '_owner_status_idx';
            $idx2 = $tableName . '_owner_idx';

            try {
                $table->dropIndex($idx1);
            } catch (\Throwable $e) {
                // ignore
            }

            try {
                $table->dropIndex($idx2);
            } catch (\Throwable $e) {
                // ignore
            }

            if (Schema::hasColumn($tableName, 'owner_id')) {
                $table->dropColumn('owner_id');
            }

            if (Schema::hasColumn($tableName, 'owner_type')) {
                $table->dropColumn('owner_type');
            }
        });
    }
};
