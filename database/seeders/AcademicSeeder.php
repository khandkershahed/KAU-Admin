<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\AcademicMenuGroup;
use App\Models\AcademicSite;
use App\Models\AcademicNavItem;
use App\Models\AcademicPage;
use App\Models\AcademicDepartment;
use App\Models\AcademicStaffSection;
use App\Models\AcademicStaffMember;

class AcademicSeeder extends Seeder
{
    public function run(): void
    {
        /* -----------------------------------------------------------
         | 1. MENU GROUPS
         |------------------------------------------------------------ */
        $facultyGroup = AcademicMenuGroup::updateOrCreate(
            ['slug' => 'faculty'],
            ['title' => 'Faculty', 'position' => 1, 'status' => 'published']
        );

        $instituteGroup = AcademicMenuGroup::updateOrCreate(
            ['slug' => 'institute'],
            ['title' => 'Institute', 'position' => 2, 'status' => 'published']
        );

        /* -----------------------------------------------------------
         | Helper: Create Site + Nav + Pages
         |------------------------------------------------------------ */
        $createSite = function (AcademicMenuGroup $group, array $siteData) {

            /* -----------------------------------------------------------
             | Create Site
             |------------------------------------------------------------ */
            $site = AcademicSite::updateOrCreate(
                ['slug' => $siteData['slug']],
                [
                    'academic_menu_group_id' => $group->id,
                    'name'                   => $siteData['name'],
                    'short_name'             => $siteData['short_name'] ?? null,
                    'short_description'      => $siteData['short_description'] ?? null,
                    'theme_primary_color'    => $siteData['theme_primary_color'] ?? null,
                    'theme_secondary_color'  => $siteData['theme_secondary_color'] ?? null,
                    'logo_path'              => $siteData['logo_path'] ?? null,
                    'position'               => $siteData['position'] ?? 0,
                    'status'                 => 'published',
                ]
            );

            /* -----------------------------------------------------------
             | NAVIGATION ITEMS (Root + Child Items)
             |------------------------------------------------------------ */
            $rootNavs = [
                'home' => ['Home', 'page'],
                'about' => ['About ' . ($site->short_name ?? $site->name), 'page'],
                'departments' => ['Departments', 'page'],
                'facilities' => ['Facilities', 'page'],
                'research' => ['Research', 'page'],
                'academic' => ['Academic', 'group']
            ];

            $navItems = [];

            $position = 1;
            foreach ($rootNavs as $key => [$label, $type]) {
                $navItems[$key] = AcademicNavItem::updateOrCreate(
                    [
                        'academic_site_id' => $site->id,
                        'menu_key'         => $key
                    ],
                    [
                        'parent_id'    => null,
                        'label'        => $label,
                        'slug'         => Str::slug($label),
                        'menu_key'     => $key,
                        'type'         => $type,
                        'external_url' => null,
                        'icon'         => null,
                        'position'     => $position++,
                        'status'       => 'published',
                    ]
                );
            }

            /* -----------------------------------------------------------
             | CHILD NAVS for "Academic" group
             |------------------------------------------------------------ */
            $academicSubPages = [
                'academic_program'  => 'Academic Program',
                'syllabus'          => 'Syllabus',
                'academic_calendar' => 'Academic Calendar',
                'class_routine'     => 'Class Routine',
                'result'            => 'Result',
            ];

            $childPosition = 1;
            foreach ($academicSubPages as $key => $label) {

                $navItems[$key] = AcademicNavItem::updateOrCreate(
                    [
                        'academic_site_id' => $site->id,
                        'menu_key'         => $key,
                    ],
                    [
                        'parent_id'    => $navItems['academic']->id,
                        'label'        => $label,
                        'slug'         => Str::slug($label),
                        'menu_key'     => $key,
                        'type'         => 'page',
                        'external_url' => null,
                        'icon'         => null,
                        'position'     => $childPosition++,
                        'status'       => 'published',
                    ]
                );
            }

            /* -----------------------------------------------------------
             | PAGES (linked to nav items)
             |------------------------------------------------------------ */
            foreach ($navItems as $key => $nav) {

                $isHome = $key === 'home';
                $isDeptPage = $key === 'departments';

                AcademicPage::updateOrCreate(
                    [
                        'academic_site_id' => $site->id,
                        'page_key'         => $key,
                    ],
                    [
                        'nav_item_id'          => $nav->id,
                        'slug'                 => $nav->slug,
                        'title'                => $nav->label,
                        'is_home'              => $isHome,
                        'is_department_boxes'  => $isDeptPage,
                        'is_faculty_members'   => false,
                        'banner_title'         => $nav->label,
                        'banner_subtitle'      => null,
                        'banner_button'        => null,
                        'banner_button_url'    => null,
                        'content'              => "<p>Demo content for {$nav->label} of {$site->name}.</p>",
                        'meta_title'           => $nav->label . ' - ' . $site->name,
                        'meta_tags'            => null,
                        'meta_description'     => "Page: {$nav->label} of {$site->name}",
                        'banner_image'         => null,
                        'og_image'             => null,
                        'status'               => 'published',
                        'position'             => $nav->position,
                    ]
                );
            }

            return $site;
        };

        /* -----------------------------------------------------------
         | CREATE SAMPLE SITES
         |------------------------------------------------------------ */
        $vabs = $createSite($facultyGroup, [
            'slug' => 'vabs',
            'name' => 'Veterinary, Animal and Biomedical Sciences',
            'short_name' => 'VABS',
            'theme_primary_color' => '#0f766e',
            'position' => 1
        ]);

        $ag = $createSite($facultyGroup, [
            'slug' => 'ag',
            'name' => 'Agriculture',
            'short_name' => 'AG',
            'theme_primary_color' => '#15803d',
            'position' => 2
        ]);

        $fos = $createSite($facultyGroup, [
            'slug' => 'fos',
            'name' => 'Fisheries & Ocean Sciences',
            'short_name' => 'FOS',
            'theme_primary_color' => '#0ea5e9',
            'position' => 3
        ]);

        $aeas = $createSite($facultyGroup, [
            'slug' => 'aeas',
            'name' => 'Agricultural Economics & Agribusiness Studies',
            'short_name' => 'AEAS',
            'theme_primary_color' => '#7c3aed',
            'position' => 4
        ]);

        $aet = $createSite($facultyGroup, [
            'slug' => 'aet',
            'name' => 'Agricultural Engineering & Technology',
            'short_name' => 'AET',
            'theme_primary_color' => '#f97316',
            'position' => 5
        ]);

        /* -----------------------------------------------------------
         | SAMPLE DEPARTMENTS & STAFF FOR EACH SITE
         |------------------------------------------------------------ */
        $sampleDepartments = [
            'administration'  => 'Administration',
            'teaching'        => 'Teaching Staff',
            'research_group'  => 'Research Group',
        ];

        $sampleSections = [
            'leadership' => 'Leadership',
            'officers'   => 'Officers',
            'faculty'    => 'Faculty Members',
        ];

        $sampleMembers = [
            ['name' => 'Dr. Arif Mahmud', 'designation' => 'Professor'],
            ['name' => 'Dr. Nusrat Khan', 'designation' => 'Associate Professor'],
            ['name' => 'Md. Kamal Hassan', 'designation' => 'Assistant Professor'],
            ['name' => 'Sadia Rahman', 'designation' => 'Lecturer'],
            ['name' => 'Rafiq Islam', 'designation' => 'Officer'],
        ];

        $sites = [$vabs, $ag, $fos, $aeas, $aet];

        foreach ($sites as $site) {

            foreach ($sampleDepartments as $slug => $title) {

                $department = AcademicDepartment::updateOrCreate(
                    [
                        'academic_site_id' => $site->id,
                        'slug'             => Str::slug($slug . '-' . $site->short_name),
                    ],
                    [
                        'title'       => $title,
                        'short_code'  => strtoupper(substr($slug, 0, 3)),
                        'description' => "Department of {$title} at {$site->name}.",
                        'position'    => 1,
                        'status'      => 'published',
                    ]
                );

                /* SECTIONS */
                $secPosition = 1;
                foreach ($sampleSections as $secSlug => $secTitle) {

                    $section = AcademicStaffSection::updateOrCreate(
                        [
                            'academic_site_id'      => $site->id,
                            'academic_department_id'=> $department->id,
                            'title'                 => $secTitle,
                        ],
                        [
                            'position' => $secPosition++,
                            'status'   => 'published',
                        ]
                    );

                    /* MEMBERS */
                    $memberPos = 1;
                    foreach ($sampleMembers as $mem) {
                        AcademicStaffMember::updateOrCreate(
                            [
                                'staff_section_id' => $section->id,
                                'name'             => $mem['name'],
                            ],
                            [
                                'designation' => $mem['designation'],
                                'email'       => Str::slug($mem['name']) . '@example.com',
                                'phone'       => '01' . rand(300000000, 999999999),
                                'image_path'  => 'academic/staff/default.png',
                                'position'    => $memberPos++,
                                'status'      => 'published',
                                'links'       => [
                                    ['icon' => 'fa-solid fa-envelope', 'url' => 'mailto:' . Str::slug($mem['name']) . '@example.com'],
                                    ['icon' => 'fa-solid fa-phone', 'url' => 'tel:+8801' . rand(300000000, 999999999)],
                                ],
                            ]
                        );
                    }
                }
            }
        }
    }
}
