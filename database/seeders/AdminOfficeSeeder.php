<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdminGroup;
use App\Models\AdminOffice;

class AdminOfficeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $groups = AdminGroup::whereIn('slug', [
            'authorities',
            'administrative',
            'directorates',
        ])->get()->keyBy('slug');

        $data = [
            'authorities' => [
                ['title' => 'Syndicate',          'slug' => 'syndicate'],
                ['title' => 'Academic Council',   'slug' => 'academic-council'],
                ['title' => 'Committees',         'slug' => 'committees'],
            ],

            'administrative' => [
                ['title' => 'Office of the VC',                         'slug' => 'office-of-the-vc'],
                ['title' => 'Office of the Treasurer',                  'slug' => 'office-of-the-treasurer'],
                ['title' => 'Office of the Registrar',                  'slug' => 'registrar-office'],
                ['title' => 'Transport Pool',                           'slug' => 'transport-pool'],
                ['title' => 'Council Section',                          'slug' => 'council-section'],
                ['title' => 'Academic Section',                         'slug' => 'academic-section'],
                ['title' => 'Public Relations & Publications Office',   'slug' => 'public-relations-publications-office'],
                ['title' => 'Central Store',                            'slug' => 'central-store'],
                ['title' => 'Central Despas',                           'slug' => 'central-despas'],
                ['title' => 'Liaison Officer For Academics',            'slug' => 'liaison-officer-for-academics'],
                ['title' => 'Finance & Accounts Division',              'slug' => 'finance-and-accounts-division'],
                ['title' => 'Planning, Developments & Works Division',  'slug' => 'planning-developments-works-division'],
                ['title' => 'ICT Cell',                                 'slug' => 'ict-cell'],
                ['title' => 'Office of the Controller of Examinations', 'slug' => 'office-of-the-controller-of-examinations'],
                ['title' => 'Engineering Division',                     'slug' => 'engineering-division'],
                ['title' => 'Health Care Center',                       'slug' => 'health-care-center'],
            ],

            'directorates' => [
                ['title' => 'Director of Student Affairs', 'slug' => 'director-of-student-affairs'],
                ['title' => 'Director of Planning',        'slug' => 'director-of-planning'],
                ['title' => 'Director of Finance',         'slug' => 'director-of-finance'],
                ['title' => 'Director of IQAC',            'slug' => 'director-of-iqac'],
                ['title' => 'Director of ICT',             'slug' => 'director-of-ict'],
            ],
        ];

        foreach ($data as $groupSlug => $offices) {
            $group = $groups->get($groupSlug);

            if (! $group) {
                continue;
            }

            foreach ($offices as $index => $office) {
                AdminOffice::updateOrCreate(
                    ['slug' => $office['slug']],
                    [
                        'group_id'      => $group->id,
                        'title'         => $office['title'],
                        'banner_image'  => null,
                        'description'   => null,
                        'meta_title'    => null,
                        'meta_tags'     => null,
                        'meta_description' => null,
                        'position'      => $index + 1,
                        'status'        => true,
                    ]
                );
            }
        }
    }
}
