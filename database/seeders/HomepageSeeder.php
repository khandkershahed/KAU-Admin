<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HomepageSection;
use App\Models\HomepageBanner;
use App\Models\HomepageVcMessage;
use App\Models\HomepageExplore;
use App\Models\HomepageExploreItem;
use App\Models\HomepageFaculty;
use App\Models\HomepageGlance;
use App\Models\HomepageGlanceItem;
use App\Models\HomepageAbout;

class HomepageSeeder extends Seeder
{
    public function run(): void
    {
        // ===========================================================
        //  1. Sections: order + visibility
        //  ===========================================================

        $sections = [
            [
                'section_key' => HomepageSection::KEY_BANNER,   // "banner"
                'position'    => 1,
            ],
            [
                'section_key' => HomepageSection::KEY_VC,       // "vc"
                'position'    => 2,
            ],
            [
                'section_key' => HomepageSection::KEY_EXPLORE,  // "explore"
                'position'    => 3,
            ],
            [
                'section_key' => HomepageSection::KEY_FACULTY,  // "faculty"
                'position'    => 4,
            ],
            [
                'section_key' => HomepageSection::KEY_GLANCE,   // "glance"
                'position'    => 5,
            ],
            [
                'section_key' => HomepageSection::KEY_ABOUT,    // "about"
                'position'    => 6,
            ],
        ];

        foreach ($sections as $data) {
            HomepageSection::updateOrCreate(
                ['section_key' => $data['section_key']],
                [
                    'position'  => $data['position'],
                    'is_active' => true,
                ]
            );
        }

        // 2. Banner slider (hero area)
        if (HomepageBanner::count() === 0) {
            HomepageBanner::create([
                'title'        => 'Shaping the Future of Agriculture',
                'subtitle'     => 'KAU empowers the next generation of innovators through research, sustainability, and modern agricultural technologies.',
                'button_text'  => 'Explore Programs',
                'button_url'   => url('/academics'),
                'image_path'   => null,    // you can upload & update later
                'position'     => 1,
            ]);

            HomepageBanner::create([
                'title'        => 'Education for a Stronger Tomorrow',
                'subtitle'     => 'From smart farming to agri-entrepreneurship, we prepare students to lead in a changing world.',
                'button_text'  => 'Discover Departments',
                'button_url'   => url('/academics/faculty'),
                'image_path'   => null,
                'position'     => 2,
            ]);
        }

        //
        // 3. VC Message

        HomepageVcMessage::firstOrCreate(
            [], // single row table
            [
                'vc_name'        => 'Prof. Dr. Md. Nazmul Ahsan',
                'vc_designation' => 'Vice-Chancellor, KAU',
                'vc_image'       => null, // set to storage path after upload
                'message_title'  => 'Message from the Vice Chancellor',
                // Short summary paraphrased from the live site
                'message_text'   => 'Welcome to Khulna Agricultural University. We are committed to quality education and research, honoring the sacrifices that shaped our nation and empowering the next generation of leaders in agriculture.',
                'button_name'    => 'Read More',
                'button_url'     => url('/about/vice-chancellor'),
            ]
        );

        //  4. Explore KAU (title + items)

        $explore = HomepageExplore::firstOrCreate(
            [],
            [
                'section_title' => 'Explore KAU',
            ]
        );

        if ($explore->items()->count() === 0) {
            $exploreItems = [
                [
                    'icon'     => 'fa-solid fa-circle-info',
                    'title'    => 'About KAU',
                    'url'      => url('/about/kau-at-a-glance'),
                    'position' => 1,
                ],
                [
                    'icon'     => 'fa-solid fa-building-columns',
                    'title'    => 'Administration',
                    'url'      => url('/administration'),
                    'position' => 2,
                ],
                [
                    'icon'     => 'fa-solid fa-bullseye',
                    'title'    => 'Mission & Vision',
                    'url'      => url('/about/mission-and-vision'),
                    'position' => 3,
                ],
                [
                    'icon'     => 'fa-solid fa-scale-balanced',
                    'title'    => 'Academic Policy Agreement',
                    'url'      => url('/academics/academic-policy-agreement'),
                    'position' => 4,
                ],
                [
                    'icon'     => 'fa-solid fa-user-graduate',
                    'title'    => 'Admission',
                    'url'      => url('/admission'),
                    'position' => 5,
                ],
            ];

            foreach ($exploreItems as $item) {
                HomepageExploreItem::create([
                    'explore_id' => $explore->id,
                    'icon'       => $item['icon'],
                    'title'      => $item['title'],
                    'url'        => $item['url'],
                    'position'   => $item['position'],
                ]);
            }
        }

        // 5. Faculties section (title/subtitle only)

        HomepageFaculty::firstOrCreate(
            [],
            [
                'section_title'    => 'Faculties of KAU',
                'section_subtitle' => 'Explore our diverse faculties, each dedicated to excellence in their respective fields.',
            ]
        );

        // 6. KAU at a Glance

        $glance = HomepageGlance::firstOrCreate(
            [],
            [
                'section_title'    => 'KAU At A Glance',
                'section_subtitle' => 'Key facts about Khulna Agricultural University.',
            ]
        );

        if ($glance->items()->count() === 0) {
            $stats = [
                [
                    'icon'     => 'fa-solid fa-building-columns',
                    'title'    => 'Faculties',
                    'number'   => '0',
                    'position' => 1,
                ],
                [
                    'icon'     => 'fa-solid fa-sitemap',
                    'title'    => 'Departments',
                    'number'   => '0',
                    'position' => 2,
                ],
                [
                    'icon'     => 'fa-solid fa-chalkboard-teacher',
                    'title'    => 'Teachers',
                    'number'   => '0',
                    'position' => 3,
                ],
                [
                    'icon'     => 'fa-solid fa-user-graduate',
                    'title'    => 'Students',
                    'number'   => '0',
                    'position' => 4,
                ],
                [
                    'icon'     => 'fa-solid fa-users',
                    'title'    => 'Officer & Staff',
                    'number'   => '0',
                    'position' => 5,
                ],
            ];

            foreach ($stats as $stat) {
                HomepageGlanceItem::create([
                    'glance_id' => $glance->id,
                    'icon'      => $stat['icon'],
                    'title'     => $stat['title'],
                    'number'    => $stat['number'],
                    'position'  => $stat['position'],
                ]);
            }
        }

        // 7. About section

        HomepageAbout::firstOrCreate(
            [],
            [
                'badge'            => 'About The University',
                'title'            => 'Khulna Agricultural University',
                'subtitle'         => 'Education, Research & Innovation.',
                // Paraphrased version of the homepage text
                'description'      => 'Khulna Agricultural University is dedicated to excellence in agricultural education and research. KAU brings together bright minds to drive sustainable growth, modern farming innovation, and technological advancement for the future of Bangladesh.',
                'experience_badge' => 'Experience',
                'experience_title' => '15+ Years',
                // image paths can be filled later from admin upload
                'images'           => [],
            ]
        );
    }
}
