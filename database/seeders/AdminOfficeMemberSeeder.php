<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdminOffice;
use App\Models\AdminOfficeSection;
use App\Models\AdminOfficeMember;

class AdminOfficeMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*
         * Helper to find section id by office slug + section title
         */
        $getSectionId = function (string $officeSlug, string $sectionTitle): ?int {
            $office = AdminOffice::where('slug', $officeSlug)->first();

            if (! $office) {
                return null;
            }

            $section = AdminOfficeSection::where('office_id', $office->id)
                ->where('title', $sectionTitle)
                ->first();

            return $section?->id;
        };

        $members = [
            // Office of the VC (head + officers)
            'office-of-the-vc' => [
                [
                    'section_title' => 'Vice-Chancellor',
                    'name'          => 'Prof. Dr. Md. Nazmul Ahsan',
                    'designation'   => 'Vice-Chancellor',
                    'email'         => 'vc@kau.ac.bd',
                    'phone'         => null,
                    'type'          => 'head',
                    'position'      => 1,
                ],
                [
                    'section_title' => 'Officers',
                    'name'          => 'Delwar Hossain',
                    'designation'   => 'Ps To Vc (In charge)',
                    'email'         => 'delwar@kau.ac.bd',
                    'phone'         => '+880 1521 576582',
                    'type'          => 'member',
                    'position'      => 1,
                ],
                [
                    'section_title' => 'Officers',
                    'name'          => 'Md. Murad Hossain',
                    'designation'   => 'Administrative Officer',
                    'email'         => 'kaumurad@gmail.com',
                    'phone'         => '+880 1629 964723',
                    'type'          => 'member',
                    'position'      => 2,
                ],
                [
                    'section_title' => 'Officers',
                    'name'          => 'Md. Abid Hossain',
                    'designation'   => 'Administrative Officer',
                    'email'         => 'hossainmdabid007@gmail.com',
                    'phone'         => '+880 1833 293355',
                    'type'          => 'member',
                    'position'      => 3,
                ],
            ],

            // Office of the Treasurer (head + officers)
            'office-of-the-treasurer' => [
                [
                    'section_title' => 'Treasurer',
                    'name'          => 'Shamim Ahmed Kamal Uddin Khan',
                    'designation'   => 'Treasurer',
                    'email'         => 'samkuk_bd@yahoo.com',
                    'phone'         => '+88 0171 6169607',
                    'type'          => 'head',
                    'position'      => 1,
                ],
                [
                    'section_title' => 'Officers',
                    'name'          => 'Shaikh Imran Ahmmed',
                    'designation'   => 'Admin Officer',
                    'email'         => 'kaushaikhimranahmed@gmail.com',
                    'phone'         => '+880 1918 050028',
                    'type'          => 'member',
                    'position'      => 1,
                ],
                [
                    'section_title' => 'Officers',
                    'name'          => 'Ziarul Golder',
                    'designation'   => 'PA to Treasure',
                    'email'         => 'ziarul.kau@gmail.com',
                    'phone'         => '+880 1937 286528',
                    'type'          => 'member',
                    'position'      => 2,
                ],
            ],

            // Liaison Officer For Academics
            'liaison-officer-for-academics' => [
                [
                    'section_title' => 'Officers',
                    'name'          => 'Md. Khirul Bashar Riaz',
                    'designation'   => 'Coordinating Officer',
                    'email'         => 'kbriaz84@gmail.com',
                    'phone'         => '+880 1731 490889',
                    'type'          => 'member',
                    'position'      => 1,
                ],
                [
                    'section_title' => 'Officers',
                    'name'          => 'Md. Najmul Haque',
                    'designation'   => 'Coordinating Officer',
                    'email'         => 'nhaque557@gmail.com',
                    'phone'         => '+880 1731 837043',
                    'type'          => 'member',
                    'position'      => 2,
                ],
            ],

            // Transport Pool
            'transport-pool' => [
                [
                    'section_title' => 'Officers',
                    'name'          => 'Alal Khan',
                    'designation'   => 'Section Officer',
                    'email'         => 'khanalal58@gmail.com',
                    'phone'         => '+880 1740 601797',
                    'type'          => 'member',
                    'position'      => 1,
                ],
            ],
        ];

        foreach ($members as $officeSlug => $officeMembers) {
            foreach ($officeMembers as $member) {
                $sectionId = $getSectionId($officeSlug, $member['section_title']);

                $office = AdminOffice::where('slug', $officeSlug)->first();
                if (! $office) {
                    continue;
                }

                AdminOfficeMember::updateOrCreate(
                    [
                        'office_id'  => $office->id,
                        'section_id' => $sectionId,
                        'name'       => $member['name'],
                    ],
                    [
                        'designation' => $member['designation'],
                        'email'       => $member['email'],
                        'phone'       => $member['phone'],
                        'image'       => null,
                        'type'        => $member['type'],
                        'position'    => $member['position'],
                    ]
                );
            }
        }
    }
}
