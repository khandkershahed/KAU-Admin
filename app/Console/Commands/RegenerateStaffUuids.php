<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AcademicStaffMember;
use App\Models\AcademicStaffSection;
use App\Models\AcademicDepartment;
use Illuminate\Support\Str;

class RegenerateStaffUuids extends Command
{
    protected $signature = 'staff:regenerate-uuids {--dry-run}';
    protected $description = 'Regenerate UUIDs for academic staff members without modifying other fields';

    public function handle()
    {
        $this->info('Starting staff UUID regeneration…');

        $dryRun = $this->option('dry-run');

        AcademicStaffMember::chunkById(100, function ($members) use ($dryRun) {
            foreach ($members as $member) {

                $section = AcademicStaffSection::find($member->staff_section_id);

                $departmentShortCode = null;
                if ($section) {
                    $departmentShortCode = AcademicDepartment::where(
                        'id',
                        $section->academic_department_id
                    )->value('short_code');
                }

                $newUuid = $this->generateMemberUuid(
                    $member->name,
                    $departmentShortCode,
                    $member->id
                );

                if ($member->uuid !== $newUuid) {
                    if ($dryRun) {
                        $this->line("DRY RUN: {$member->uuid} → {$newUuid}");
                    } else {
                        $member->updateQuietly([
                            'uuid' => $newUuid,
                        ]);

                        $this->line("Updated: {$member->uuid} → {$newUuid}");
                    }
                }
            }
        });

        $this->info($dryRun ? 'Dry run completed.' : 'UUID regeneration completed.');
    }

    /**
     * Same logic as your controller, but excludes current record
     */
    private function generateMemberUuid(string $name, ?string $departmentShortCode, int $ignoreId): string
    {
        $base = Str::slug($name) ?: 'faculty';

        // 1) name only
        if (!AcademicStaffMember::where('uuid', $base)->where('id', '!=', $ignoreId)->exists()) {
            return $base;
        }

        // 2) name + department
        $dept = null;
        if ($departmentShortCode) {
            $dept = Str::of($departmentShortCode)
                ->lower()
                ->ascii()
                ->replaceMatches('/[^a-z0-9]+/', '')
                ->toString();
        }

        if ($dept) {
            $uuid = "{$base}-{$dept}";

            if (!AcademicStaffMember::where('uuid', $uuid)->where('id', '!=', $ignoreId)->exists()) {
                return $uuid;
            }
        }

        // 3) numeric suffix
        $i = 1;
        while (
            AcademicStaffMember::where('uuid', "{$base}-{$i}")
                ->where('id', '!=', $ignoreId)
                ->exists()
        ) {
            $i++;
        }

        return "{$base}-{$i}";
    }
}
