<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use App\Models\AcademicPage;
use App\Models\AcademicSite;
use App\Models\AcademicNavItem;
use Illuminate\Database\Seeder;
use App\Models\AcademicMenuGroup;
use App\Models\AcademicDepartment;
use App\Models\AcademicStaffMember;
use App\Models\AcademicStaffSection;

class AcademicSeeder extends Seeder
{
    public function run(): void
    {
        // ============= GROUPS =============
        $facultyGroup = AcademicMenuGroup::updateOrCreate(
            ['slug' => 'faculty'],
            ['title' => 'Faculty', 'position' => 1, 'is_active' => true]
        );

        $instituteGroup = AcademicMenuGroup::updateOrCreate(
            ['slug' => 'institute'],
            ['title' => 'Institute', 'position' => 2, 'is_active' => true]
        );

        // helper to create sites + minimal pages + nav
        $createSite = function (AcademicMenuGroup $group, array $data) {
            $site = AcademicSite::updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'academic_menu_group_id' => $group->id,
                    'name'                   => $data['name'],
                    'short_name'             => $data['short_name'] ?? null,
                    'base_url'               => $data['base_url'] ?? null,
                    'subdomain'              => $data['subdomain'] ?? null,
                    'short_description'      => $data['short_description'] ?? null,
                    'theme_primary_color'    => $data['theme_primary_color'] ?? null,
                    'theme_secondary_color'  => $data['theme_secondary_color'] ?? null,
                    'logo_path'              => $data['logo_path'] ?? null,
                    'menu_order'             => $data['menu_order'] ?? 0,
                    'status'                 => 'published',
                ]
            );

            // ensure exactly 1 home page
            $homePage = AcademicPage::updateOrCreate(
                [
                    'academic_site_id' => $site->id,
                    'page_key'         => 'home',
                ],
                [
                    'slug'              => 'home',
                    'title'             => $site->name,
                    'is_home'           => true,
                    'banner_image'      => null,
                    'content'           => '<div class=\"elementor-element elementor-element-cd45e65 elementor-widget elementor-widget-heading\" data-id=\"cd45e65\" data-element_type=\"widget\" data-widget_type=\"heading.default\">\r\n<div class=\"elementor-widget-container\">\r\n<h2 class=\"elementor-heading-title elementor-size-default\">Undergraduate Program Admission Information</h2>\r\n</div>\r\n</div>\r\n<div class=\"elementor-element elementor-element-a717c2d elementor-widget elementor-widget-text-editor\" data-id=\"a717c2d\" data-element_type=\"widget\" data-widget_type=\"text-editor.default\">\r\n<div class=\"elementor-widget-container\">\r\n<p>Under the Cluster System Admission in Agricultural Degree Offering, Khulna Agricultural University offers admission for Undergraduate Programs (Bachelor Degrees) along with 8 Public Universities of Bangladesh.</p>\r\n<p>Offered Academic Programmes:</p>\r\n</div>\r\n</div>\r\n<div class=\"elementor-element elementor-element-40a1ebd bdt-hide-entries-on-mobile-yes elementor-widget elementor-widget-bdt-table\" data-id=\"40a1ebd\" data-element_type=\"widget\" data-widget_type=\"bdt-table.default\">\r\n<div class=\"elementor-widget-container\">\r\n<div id=\"bdt-table-40a1ebd\" class=\"bdt-table bdt-overflow-auto \">\r\n<table class=\"bdt-static-table\">\r\n<thead>\r\n<tr>\r\n<th class=\"bdt-static-column-cell elementor-repeater-item-163ab8e\">\r\n<div class=\"bdt-static-column-cell-wrap\">\r\n<div class=\"bdt-static-column-cell-text\">Faculty</div>\r\n</div>\r\n</th>\r\n<th class=\"bdt-static-column-cell elementor-repeater-item-6b65327\">\r\n<div class=\"bdt-static-column-cell-wrap\">\r\n<div class=\"bdt-static-column-cell-text\">Degree</div>\r\n</div>\r\n</th>\r\n<th class=\"bdt-static-column-cell elementor-repeater-item-e7beaf3\">\r\n<div class=\"bdt-static-column-cell-wrap\">\r\n<div class=\"bdt-static-column-cell-text\">Duration</div>\r\n</div>\r\n</th>\r\n<th class=\"bdt-static-column-cell elementor-repeater-item-90b19b3\">\r\n<div class=\"bdt-static-column-cell-wrap\">\r\n<div class=\"bdt-static-column-cell-text\">Total Credit Hour</div>\r\n</div>\r\n</th>\r\n<th class=\"bdt-static-column-cell elementor-repeater-item-9ed8ebf\">\r\n<div class=\"bdt-static-column-cell-wrap\">\r\n<div class=\"bdt-static-column-cell-text\">Total Seats</div>\r\n</div>\r\n</th>\r\n</tr>\r\n</thead>\r\n<tbody>\r\n<tr>\r\n<td class=\"bdt-static-body-row-cell elementor-repeater-item-386311c\">\r\n<div class=\"bdt-static-body-row-cell-wrap\">\r\n<div class=\"bdt-static-body-row-cell-text\">Veterinary, Animal and Biomedical Sciences</div>\r\n</div>\r\n</td>\r\n<td class=\"bdt-static-body-row-cell elementor-repeater-item-3a9a99c\">\r\n<div class=\"bdt-static-body-row-cell-wrap\">\r\n<div class=\"bdt-static-body-row-cell-text\">B.Sc. VS &amp; AH</div>\r\n</div>\r\n</td>\r\n<td class=\"bdt-static-body-row-cell elementor-repeater-item-eae317d\">\r\n<div class=\"bdt-static-body-row-cell-wrap\">\r\n<div class=\"bdt-static-body-row-cell-text\">10- Semester (5-year)</div>\r\n</div>\r\n</td>\r\n<td class=\"bdt-static-body-row-cell elementor-repeater-item-98c1429\">\r\n<div class=\"bdt-static-body-row-cell-wrap\">\r\n<div class=\"bdt-static-body-row-cell-text\">188</div>\r\n</div>\r\n</td>\r\n<td class=\"bdt-static-body-row-cell elementor-repeater-item-0f7936d\">\r\n<div class=\"bdt-static-body-row-cell-wrap\">\r\n<div class=\"bdt-static-body-row-cell-text\">30</div>\r\n</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td class=\"bdt-static-body-row-cell elementor-repeater-item-63801cb\">\r\n<div class=\"bdt-static-body-row-cell-wrap\">\r\n<div class=\"bdt-static-body-row-cell-text\">Agriculture</div>\r\n</div>\r\n</td>\r\n<td class=\"bdt-static-body-row-cell elementor-repeater-item-537dbc5\">\r\n<div class=\"bdt-static-body-row-cell-wrap\">\r\n<div class=\"bdt-static-body-row-cell-text\">B.Sc. Agriculture (Hons.)</div>\r\n</div>\r\n</td>\r\n<td class=\"bdt-static-body-row-cell elementor-repeater-item-b9ad2b3\">\r\n<div class=\"bdt-static-body-row-cell-wrap\">\r\n<div class=\"bdt-static-body-row-cell-text\">8- Semester (4-year)</div>\r\n</div>\r\n</td>\r\n<td class=\"bdt-static-body-row-cell elementor-repeater-item-845cf8d\">\r\n<div class=\"bdt-static-body-row-cell-wrap\">\r\n<div class=\"bdt-static-body-row-cell-text\">180</div>\r\n</div>\r\n</td>\r\n<td class=\"bdt-static-body-row-cell elementor-repeater-item-dddae7c\">\r\n<div class=\"bdt-static-body-row-cell-wrap\">\r\n<div class=\"bdt-static-body-row-cell-text\">30</div>\r\n</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td class=\"bdt-static-body-row-cell elementor-repeater-item-bd963a9\">\r\n<div class=\"bdt-static-body-row-cell-wrap\">\r\n<div class=\"bdt-static-body-row-cell-text\">Fisheries &amp; Ocean Sciences</div>\r\n</div>\r\n</td>\r\n<td class=\"bdt-static-body-row-cell elementor-repeater-item-ce898f4\">\r\n<div class=\"bdt-static-body-row-cell-wrap\">\r\n<div class=\"bdt-static-body-row-cell-text\">B.Sc. Fisheries (Hons.)</div>\r\n</div>\r\n</td>\r\n<td class=\"bdt-static-body-row-cell elementor-repeater-item-e00e892\">\r\n<div class=\"bdt-static-body-row-cell-wrap\">\r\n<div class=\"bdt-static-body-row-cell-text\">8- Semester (4-year)</div>\r\n</div>\r\n</td>\r\n<td class=\"bdt-static-body-row-cell elementor-repeater-item-a9e3cd0\">\r\n<div class=\"bdt-static-body-row-cell-wrap\">\r\n<div class=\"bdt-static-body-row-cell-text\">167</div>\r\n</div>\r\n</td>\r\n<td class=\"bdt-static-body-row-cell elementor-repeater-item-caa3bd0\">\r\n<div class=\"bdt-static-body-row-cell-wrap\">\r\n<div class=\"bdt-static-body-row-cell-text\">30</div>\r\n</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td class=\"bdt-static-body-row-cell elementor-repeater-item-7257a59\">\r\n<div class=\"bdt-static-body-row-cell-wrap\">\r\n<div class=\"bdt-static-body-row-cell-text\">Agricultural Economics &amp; Agribusiness Studies</div>\r\n</div>\r\n</td>\r\n<td class=\"bdt-static-body-row-cell elementor-repeater-item-703fe3d\">\r\n<div class=\"bdt-static-body-row-cell-wrap\">\r\n<div class=\"bdt-static-body-row-cell-text\">B.Sc. Ag. Econ. (Hons.)</div>\r\n</div>\r\n</td>\r\n<td class=\"bdt-static-body-row-cell elementor-repeater-item-d440ffa\">\r\n<div class=\"bdt-static-body-row-cell-wrap\">\r\n<div class=\"bdt-static-body-row-cell-text\">8- Semester (4-year)</div>\r\n</div>\r\n</td>\r\n<td class=\"bdt-static-body-row-cell elementor-repeater-item-0eeab95\">\r\n<div class=\"bdt-static-body-row-cell-wrap\">\r\n<div class=\"bdt-static-body-row-cell-text\">170</div>\r\n</div>\r\n</td>\r\n<td class=\"bdt-static-body-row-cell elementor-repeater-item-9562189\">\r\n<div class=\"bdt-static-body-row-cell-wrap\">\r\n<div class=\"bdt-static-body-row-cell-text\">30</div>\r\n</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td class=\"bdt-static-body-row-cell elementor-repeater-item-e76f68c\">\r\n<div class=\"bdt-static-body-row-cell-wrap\">\r\n<div class=\"bdt-static-body-row-cell-text\">Agricultural Engineering &amp; Technology</div>\r\n</div>\r\n</td>\r\n<td class=\"bdt-static-body-row-cell elementor-repeater-item-7b601c8\">\r\n<div class=\"bdt-static-body-row-cell-wrap\">\r\n<div class=\"bdt-static-body-row-cell-text\">B.Sc. Ag. Engg.</div>\r\n</div>\r\n</td>\r\n<td class=\"bdt-static-body-row-cell elementor-repeater-item-8350d03\">\r\n<div class=\"bdt-static-body-row-cell-wrap\">\r\n<div class=\"bdt-static-body-row-cell-text\">8- Semester (4-year)</div>\r\n</div>\r\n</td>\r\n<td class=\"bdt-static-body-row-cell elementor-repeater-item-d64eb05\">\r\n<div class=\"bdt-static-body-row-cell-wrap\">\r\n<div class=\"bdt-static-body-row-cell-text\">172</div>\r\n</div>\r\n</td>\r\n<td class=\"bdt-static-body-row-cell elementor-repeater-item-7dfac05\">\r\n<div class=\"bdt-static-body-row-cell-wrap\">\r\n<div class=\"bdt-static-body-row-cell-text\">30</div>\r\n</div>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</div>\r\n</div>\r\n</div>\r\n<div class=\"elementor-element elementor-element-043a012 elementor-widget elementor-widget-text-editor\" data-id=\"043a012\" data-element_type=\"widget\" data-widget_type=\"text-editor.default\">\r\n<div class=\"elementor-widget-container\">Minimum Academic Requirement:\r\n<ul>\r\n<li>The applicant must have completed SSC and HSC (or equivalent) in recent years from science section with Biology, Chemistry, Physics and Mathematics.</li>\r\n<li>S/he must have secured a minimum GPA of 4.00 on a scale of 5.00 in SSC and HSC (or equivalent) and total GPA of 8.50.</li>\r\n</ul>\r\nFor more information about admission visit:&nbsp;<a href=\"https://acas.edu.bd/\" target=\"_blank\" rel=\"noopener\">https://acas.edu.bd/</a></div>\r\n</div>',
                    'meta_title'        => $site->name . ' - Home',
                    'meta_description'  => 'Homepage of ' . $site->name,
                    'og_image'          => null,

                    // home-only fields
                    'banner_title'      => $site->name,
                    'banner_subtitle'   => null,
                    'banner_button'     => 'Learn More',
                    'banner_button_url' => '#',

                    'is_active'         => true,
                    'position'          => 1,
                ]
            );

            // About page
            $aboutPage = AcademicPage::updateOrCreate(
                [
                    'academic_site_id' => $site->id,
                    'page_key'         => 'about',
                ],
                [
                    'slug'             => 'about-' . $site->slug,
                    'title'            => 'About ' . ($site->short_name ?? $site->name),
                    'subtitle'         => null,
                    'is_home'          => false,
                    'banner_image'     => null,
                    'content'          => '<p>About page content for ' . e($site->name) . '.</p>',
                    'meta_title'       => 'About ' . $site->name,
                    'meta_tags'        => null,
                    'meta_description' => 'About ' . $site->name,
                    'og_image'         => null,
                    'is_active'        => true,
                    'position'         => 2,
                ]
            );

            // Facilities
            $facilitiesPage = AcademicPage::updateOrCreate(
                [
                    'academic_site_id' => $site->id,
                    'page_key'         => 'facilities',
                ],
                [
                    'slug'             => 'facilities',
                    'title'            => 'Facilities',
                    'subtitle'         => null,
                    'is_home'          => false,
                    'banner_image'     => null,
                    'content'          => '<p>Facilities content for ' . e($site->name) . '.</p>',
                    'meta_title'       => 'Facilities - ' . $site->name,
                    'meta_tags'        => null,
                    'meta_description' => 'Facilities of ' . $site->name,
                    'og_image'         => null,
                    'is_active'        => true,
                    'position'         => 3,
                ]
            );

            // Research
            $researchPage = AcademicPage::updateOrCreate(
                [
                    'academic_site_id' => $site->id,
                    'page_key'         => 'research',
                ],
                [
                    'slug'             => 'research',
                    'title'            => 'Research',
                    'subtitle'         => null,
                    'is_home'          => false,
                    'banner_image'     => null,
                    'content'          => '<p>Research content for ' . e($site->name) . '.</p>',
                    'meta_title'       => 'Research - ' . $site->name,
                    'meta_tags'        => null,
                    'meta_description' => 'Research at ' . $site->name,
                    'og_image'         => null,
                    'is_active'        => true,
                    'position'         => 4,
                ]
            );

            // Academic subpages
            $academicSubpages = [
                'academic_program'   => 'Academic Program',
                'syllabus'           => 'Syllabus',
                'academic_calendar'  => 'Academic Calendar',
                'class_routine'      => 'Class Routine',
                'result'             => 'Result',
            ];

            $academicPages = [];
            $pos = 5;
            foreach ($academicSubpages as $key => $title) {
                $academicPages[$key] = AcademicPage::updateOrCreate(
                    [
                        'academic_site_id' => $site->id,
                        'page_key'         => $key,
                    ],
                    [
                        'slug'             => Str::slug($title),
                        'title'            => $title,
                        'subtitle'         => null,
                        'is_home'          => false,
                        'banner_image'     => null,
                        'content'          => '<p>' . $title . ' content for ' . e($site->name) . '.</p>',
                        'meta_title'       => $title . ' - ' . $site->name,
                        'meta_tags'        => null,
                        'meta_description' => $title . ' of ' . $site->name,
                        'og_image'         => null,
                        'is_active'        => true,
                        'position'         => $pos++,
                    ]
                );
            }

            // Now nav items
            $navHome = AcademicNavItem::updateOrCreate(
                [
                    'academic_site_id' => $site->id,
                    'menu_key'         => 'home',
                ],
                [
                    'parent_id'    => null,
                    'label'        => 'Home',
                    'type'         => 'page',
                    'page_id'      => $homePage->id,
                    'route_path'   => '/',
                    'external_url' => null,
                    'position'     => 1,
                    'is_active'    => true,
                ]
            );

            $navAbout = AcademicNavItem::updateOrCreate(
                [
                    'academic_site_id' => $site->id,
                    'menu_key'         => 'about',
                ],
                [
                    'parent_id'        => null,
                    'label'            => 'About ' . ($site->short_name ?? $site->name),
                    'type'             => 'page',
                    'page_id'          => $aboutPage->id,
                    'route_path'       => '/about-' . ($site->short_name),
                    'external_url'     => null,
                    'position'         => 2,
                    'is_active'   => true,
                ]
            );

            $navDepartments = AcademicNavItem::updateOrCreate(
                [
                    'academic_site_id' => $site->id,
                    'menu_key'         => 'departments',
                ],
                [
                    'parent_id'   => null,
                    'label'       => 'Departments',
                    'type'        => 'route',
                    'page_id'     => null,
                    'route_path'  => '/departments',
                    'external_url' => null,
                    'position'    => 3,
                    'is_active'   => true,
                ]
            );

            $navFacilities = AcademicNavItem::updateOrCreate(
                [
                    'academic_site_id' => $site->id,
                    'menu_key'         => 'facilities',
                ],
                [
                    'parent_id'   => null,
                    'label'       => 'Facilities',
                    'type'        => 'page',
                    'page_id'     => $facilitiesPage->id,
                    'route_path'  => '/facilities',
                    'external_url' => null,
                    'position'    => 4,
                    'is_active'   => true,
                ]
            );

            $navFacultyMember = AcademicNavItem::updateOrCreate(
                [
                    'academic_site_id' => $site->id,
                    'menu_key'         => 'faculty_member',
                ],
                [
                    'parent_id'   => null,
                    'label'       => 'Faculty Member',
                    'type'        => 'route',
                    'page_id'     => null,
                    'route_path'  => '/faculty-member',
                    'external_url' => null,
                    'position'    => 5,
                    'is_active'   => true,
                ]
            );

            $navAcademic = AcademicNavItem::updateOrCreate(
                [
                    'academic_site_id' => $site->id,
                    'menu_key'         => 'academic',
                ],
                [
                    'parent_id'   => null,
                    'label'       => 'Academic',
                    'type'        => 'group',
                    'page_id'     => null,
                    'route_path'  => null,
                    'external_url' => null,
                    'position'    => 6,
                    'is_active'   => true,
                ]
            );

            $childPos = 1;
            foreach ($academicPages as $key => $page) {
                AcademicNavItem::updateOrCreate(
                    [
                        'academic_site_id' => $site->id,
                        'menu_key'         => $key,
                    ],
                    [
                        'parent_id'   => $navAcademic->id,
                        'label'       => $page->title,
                        'type'        => 'page',
                        'page_id'     => $page->id,
                        'route_path'  => '/' . Str::slug($page->title),
                        'external_url' => null,
                        'position'    => $childPos++,
                        'is_active'   => true,
                    ]
                );
            }

            $navResearch = AcademicNavItem::updateOrCreate(
                [
                    'academic_site_id' => $site->id,
                    'menu_key'         => 'research',
                ],
                [
                    'parent_id'   => null,
                    'label'       => 'Research',
                    'type'        => 'page',
                    'page_id'     => $researchPage->id,
                    'route_path'  => "/research",
                    'external_url' => null,
                    'position'    => 7,
                    'is_active'   => true,
                ]
            );

            return $site;
        };

        // ============= SITES =============
        $vabs = $createSite($facultyGroup, [
            'slug'                => 'vabs',
            'name'                => 'Veterinary, Animal and Biomedical Sciences',
            'short_name'          => 'VABS',
            'base_url'            => '/vabs',
            'theme_primary_color' => '#0f766e',
            'menu_order'          => 1,
        ]);

        $ag = $createSite($facultyGroup, [
            'slug'                => 'ag',
            'name'                => 'Agriculture',
            'short_name'          => 'AG',
            'base_url'            => '/ag',
            'theme_primary_color' => '#15803d',
            'menu_order'          => 2,
        ]);

        $fos = $createSite($facultyGroup, [
            'slug'                => 'fos',
            'name'                => 'Fisheries & Ocean Sciences',
            'short_name'          => 'FOS',
            'base_url'            => '/fos',
            'theme_primary_color' => '#0ea5e9',
            'menu_order'          => 3,
        ]);

        $aeas = $createSite($facultyGroup, [
            'slug'                => 'aeas',
            'name'                => 'Agricultural Economics & Agribusiness Studies',
            'short_name'          => 'AEAS',
            'base_url'            => '/aeas',
            'theme_primary_color' => '#7c3aed',
            'menu_order'          => 4,
        ]);

        $aet = $createSite($facultyGroup, [
            'slug'                => 'aet',
            'name'                => 'Agricultural Engineering & Technology',
            'short_name'          => 'AET',
            'base_url'            => '/aet',
            'theme_primary_color' => '#f97316',
            'menu_order'          => 5,
        ]);

        // ============= EXAMPLE DEPARTMENTS & STAFF FOR VABS =============
        $dept = AcademicDepartment::updateOrCreate(
            [
                'academic_site_id' => $vabs->id,
                'short_code'       => 'VAH',
            ],
            [
                'title'       => 'Anatomy and Histology',
                'slug'        => 'anatomy-and-histology',
                'description' => null,
                'position'    => 1,
                'is_active'   => true,
            ]
        );

        $vcSection = AcademicStaffSection::updateOrCreate(
            [
                'academic_site_id'      => $vabs->id,
                'academic_department_id' => $dept->id,
                'title'                 => 'Vice-Chancellor',
            ],
            [
                'position' => 1,
            ]
        );

        AcademicStaffMember::updateOrCreate(
            [
                'staff_section_id' => $vcSection->id,
                'email'            => 'vc@kau.ac.bd',
                'name'             => 'Prof. Dr. Md. Nazmul Ahsan',
            ],
            [
                'designation' => 'Vice-Chancellor',
                'phone'       => null,
                'image_path'  => null,
                'position'    => 1,
                'links'       => [
                    [
                        'icon' => 'fa-solid fa-google-scholar',
                        'url'  => 'https://example.com/google-scholar',
                    ],
                ],
            ]
        );
    }
}
