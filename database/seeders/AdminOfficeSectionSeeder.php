<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdminOffice;
use App\Models\AdminOfficeSection;

class AdminOfficeSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // sections for specific offices that actually show structured blocks
        $sections = [
            'office-of-the-vc' => [
                [
                    'title'        => 'Vice-Chancellor',
                    'section_type' => 'officer_cards',
                    'position'     => 1,
                ],
                [
                    'title'        => 'Officers',
                    'section_type' => 'officer_cards',
                    'position'     => 2,
                ],
            ],

            'office-of-the-treasurer' => [
                [
                    'title'        => 'Treasurer',
                    'section_type' => 'officer_cards',
                    'position'     => 1,
                ],
                [
                    'title'        => 'Officers',
                    'section_type' => 'officer_cards',
                    'position'     => 2,
                ],
            ],

            'liaison-officer-for-academics' => [
                [
                    'title'        => 'Officers',
                    'section_type' => 'officer_cards',
                    'position'     => 1,
                ],
            ],

            'transport-pool' => [
                [
                    'title'        => 'Officers',
                    'section_type' => 'officer_cards',
                    'position'     => 1,
                ],
            ],
        ];

        foreach ($sections as $officeSlug => $officeSections) {
            $office = AdminOffice::where('slug', $officeSlug)->first();

            if (! $office) {
                continue;
            }

            foreach ($officeSections as $section) {
                AdminOfficeSection::updateOrCreate(
                    [
                        'office_id' => $office->id,
                        'title'     => $section['title'],
                    ],
                    [
                        'section_type' => $section['section_type'],
                        'content'      => $section['content'] ?? null,
                        'extra'        => $section['extra'] ?? null,
                        'position'     => $section['position'],
                        'status'       => true,
                    ]
                );
            }
        }
    }
}
