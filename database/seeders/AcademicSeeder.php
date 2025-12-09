<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AcademicMenuGroup;
use App\Models\AcademicSite;
use App\Models\AcademicNavItem;
use App\Models\AcademicPage;
use App\Models\AcademicPageSection;
use App\Models\AcademicDepartment;
use App\Models\AcademicStaffGroup;
use App\Models\AcademicStaffMember;
use App\Models\AcademicHomeWidget;
use Illuminate\Support\Str;

class AcademicSeeder extends Seeder
{
    public function run(): void
    {
        // ===== GROUPS =====
        $faculty = AcademicMenuGroup::updateOrCreate(
            ['slug' => 'faculty'],
            ['title' => 'Faculty', 'position' => 1, 'is_active' => true]
        );

        $institute = AcademicMenuGroup::updateOrCreate(
            ['slug' => 'institute'],
            ['title' => 'Institute', 'position' => 2, 'is_active' => true]
        );

        // Helper to create site
        $createSite = function (AcademicMenuGroup $group, array $data) {
            return AcademicSite::updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'academic_menu_group_id' => $group->id,
                    'name'                   => $data['name'],
                    'short_name'             => $data['short_name'],
                    'base_url'               => $data['base_url'] ?? '/' . $data['slug'],
                    'subdomain'              => $data['subdomain'] ?? null,
                    'theme_primary_color'    => $data['theme_primary_color'] ?? '#0f766e',
                    'theme_secondary_color'  => $data['theme_secondary_color'] ?? null,
                    'logo_path'              => $data['logo_path'] ?? null,
                    'menu_order'             => $data['menu_order'] ?? 0,
                    'status'                 => 'published',
                    'config'                 => $data['config'] ?? null,
                ]
            );
        };

        // ===== SITES =====
        $vabs = $createSite($faculty, [
            'slug'        => 'vabs',
            'name'        => 'Veterinary, Animal and Biomedical Sciences',
            'short_name'  => 'VABS',
            'base_url'    => '/vabs',
            'menu_order'  => 1,
        ]);

        $ag = $createSite($faculty, [
            'slug'        => 'ag',
            'name'        => 'Agriculture',
            'short_name'  => 'AG',
            'base_url'    => '/ag',
            'menu_order'  => 2,
        ]);

        $fos = $createSite($faculty, [
            'slug'        => 'fos',
            'name'        => 'Fisheries & Ocean Sciences',
            'short_name'  => 'FOS',
            'base_url'    => '/fos',
            'menu_order'  => 3,
        ]);

        $aeas = $createSite($faculty, [
            'slug'        => 'aeas',
            'name'        => 'Agricultural Economics & Agribusiness Studies',
            'short_name'  => 'AEAS',
            'base_url'    => '/aeas',
            'menu_order'  => 4,
        ]);

        $aet = $createSite($faculty, [
            'slug'        => 'aet',
            'name'        => 'Agricultural Engineering & Technology',
            'short_name'  => 'AET',
            'base_url'    => '/aet',
            'menu_order'  => 5,
        ]);

        $gti = $createSite($institute, [
            'slug'        => 'gti',
            'name'        => 'Graduate Training Institute',
            'short_name'  => 'GTI',
            'base_url'    => '/graduate-training-institute',
            'menu_order'  => 1,
        ]);

        // ========= BASIC CONTENT FOR VABS ONLY (others can be added similarly) =========

        // --- Pages for VABS ---
        $aboutVabs = AcademicPage::updateOrCreate(
            ['academic_site_id' => $vabs->id, 'page_key' => 'about'],
            [
                'slug'             => 'about-vabs',
                'title'            => 'About VABS',
                'subtitle'         => null,
                'page_type'        => 'custom',
                'banner_title'     => 'About the Faculty of VABS',
                'banner_subtitle'  => 'Veterinary, Animal and Biomedical Sciences',
                'banner_image_path'=> null,
                'meta_title'       => 'About VABS - KAU',
                'meta_description' => 'Information about the Faculty of Veterinary, Animal and Biomedical Sciences.',
                'is_active'        => true,
                'position'         => 1,
            ]
        );

        AcademicPageSection::updateOrCreate(
            ['academic_page_id' => $aboutVabs->id, 'position' => 1],
            [
                'section_key' => 'intro',
                'title'       => 'Welcome to VABS',
                'subtitle'    => null,
                'content'     => '<p>VABS focuses on veterinary medicine, animal health, and biomedical sciences.</p>',
                'extra'       => null,
            ]
        );

        $facilitiesVabs = AcademicPage::updateOrCreate(
            ['academic_site_id' => $vabs->id, 'page_key' => 'facilities'],
            [
                'slug'            => 'facilities',
                'title'           => 'Facilities',
                'page_type'       => 'custom',
                'banner_title'    => 'Facilities',
                'banner_subtitle' => 'Modern labs and clinical facilities',
                'is_active'       => true,
                'position'        => 2,
            ]
        );

        $researchVabs = AcademicPage::updateOrCreate(
            ['academic_site_id' => $vabs->id, 'page_key' => 'research'],
            [
                'slug'            => 'research',
                'title'           => 'Research',
                'page_type'       => 'custom',
                'banner_title'    => 'Research at VABS',
                'is_active'       => true,
                'position'        => 3,
            ]
        );

        // Academic subpages
        $academicPages = [
            'academic_program'   => 'Academic Program',
            'syllabus'           => 'Syllabus',
            'academic_calendar'  => 'Academic Calendar',
            'class_routine'      => 'Class Routine',
            'result'             => 'Result',
        ];

        $order = 1;
        foreach ($academicPages as $key => $title) {
            AcademicPage::updateOrCreate(
                ['academic_site_id' => $vabs->id, 'page_key' => $key],
                [
                    'slug'        => Str::slug($title),
                    'title'       => $title,
                    'page_type'   => 'academic_subpage',
                    'banner_title'=> $title,
                    'is_active'   => true,
                    'position'    => $order++,
                ]
            );
        }

        // --- Nav items for VABS ---
        $homeNav = AcademicNavItem::updateOrCreate(
            ['academic_site_id' => $vabs->id, 'menu_key' => 'home', 'parent_id' => null],
            [
                'label'     => 'Home',
                'type'      => 'route',
                'route_name'=> 'frontend.academic.site.home',
                'position'  => 1,
                'is_active' => true,
            ]
        );

        $aboutNav = AcademicNavItem::updateOrCreate(
            ['academic_site_id' => $vabs->id, 'menu_key' => 'about'],
            [
                'label'     => 'About VABS',
                'type'      => 'page',
                'page_id'   => $aboutVabs->id,
                'position'  => 2,
                'is_active' => true,
            ]
        );

        $departmentsNav = AcademicNavItem::updateOrCreate(
            ['academic_site_id' => $vabs->id, 'menu_key' => 'departments'],
            [
                'label'     => 'Departments',
                'type'      => 'route',
                'route_name'=> 'frontend.academic.site.departments',
                'position'  => 3,
                'is_active' => true,
            ]
        );

        $facilitiesNav = AcademicNavItem::updateOrCreate(
            ['academic_site_id' => $vabs->id, 'menu_key' => 'facilities'],
            [
                'label'     => 'Facilities',
                'type'      => 'page',
                'page_id'   => $facilitiesVabs->id,
                'position'  => 4,
                'is_active' => true,
            ]
        );

        $facultyMemberNav = AcademicNavItem::updateOrCreate(
            ['academic_site_id' => $vabs->id, 'menu_key' => 'faculty_member'],
            [
                'label'     => 'Faculty Member',
                'type'      => 'route',
                'route_name'=> 'frontend.academic.site.staff',
                'position'  => 5,
                'is_active' => true,
            ]
        );

        $academicNav = AcademicNavItem::updateOrCreate(
            ['academic_site_id' => $vabs->id, 'menu_key' => 'academic', 'parent_id' => null],
            [
                'label'     => 'Academic',
                'type'      => 'route',
                'route_name'=> 'frontend.academic.site.academic',
                'position'  => 6,
                'is_active' => true,
            ]
        );

        $researchNav = AcademicNavItem::updateOrCreate(
            ['academic_site_id' => $vabs->id, 'menu_key' => 'research'],
            [
                'label'     => 'Research',
                'type'      => 'page',
                'page_id'   => $researchVabs->id,
                'position'  => 7,
                'is_active' => true,
            ]
        );

        // children under Academic
        $position = 1;
        foreach ($academicPages as $key => $title) {
            $page = AcademicPage::where('academic_site_id', $vabs->id)
                ->where('page_key', $key)->first();

            AcademicNavItem::updateOrCreate(
                [
                    'academic_site_id' => $vabs->id,
                    'parent_id'        => $academicNav->id,
                    'menu_key'         => $key,
                ],
                [
                    'label'     => $title,
                    'type'      => 'page',
                    'page_id'   => $page?->id,
                    'position'  => $position++,
                    'is_active' => true,
                ]
            );
        }

        // --- Departments + Staff for VABS (sample) ---
        $vah = AcademicDepartment::updateOrCreate(
            ['academic_site_id' => $vabs->id, 'short_code' => 'VAH'],
            [
                'title'       => 'Anatomy and Histology',
                'slug'        => 'anatomy-and-histology',
                'description' => null,
                'position'    => 1,
                'is_active'   => true,
            ]
        );

        $officersGroup = AcademicStaffGroup::updateOrCreate(
            [
                'academic_site_id'      => $vabs->id,
                'academic_department_id'=> $vah->id,
                'title'                 => 'Officers',
            ],
            ['position' => 1]
        );

        AcademicStaffMember::updateOrCreate(
            [
                'staff_group_id' => $officersGroup->id,
                'email'          => 'delwar@kau.ac.bd',
            ],
            [
                'name'        => 'Delwar Hossain',
                'designation' => 'Ps To Vc (In charge)',
                'phone'       => '+880 1521 576582',
                'image_path'  => null,
                'position'    => 1,
                'links'       => [
                    [
                        'icon' => 'fa-solid fa-google-scholar',
                        'url'  => 'https://example.com/scholar/delwar',
                    ],
                ],
            ]
        );

        // --- Home widgets for VABS (sample) ---
        AcademicHomeWidget::updateOrCreate(
            [
                'academic_site_id' => $vabs->id,
                'widget_type'      => 'hero',
                'position'         => 1,
            ],
            [
                'title'       => 'Welcome to VABS',
                'subtitle'    => 'Faculty of Veterinary, Animal and Biomedical Sciences',
                'content'     => '<p>Fostering excellence in veterinary education and research.</p>',
                'button_text' => 'Learn More',
                'button_url'  => '/vabs/about-vabs',
                'is_active'   => true,
            ]
        );
    }
}
