<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AcademicMenuGroup;
use App\Models\AcademicUnit;
use App\Models\AcademicUnitDepartment;
use App\Models\AcademicUnitStaffSection;
use App\Models\AcademicUnitStaffMember;

class AcademicSeeder extends Seeder
{
    public function run(): void
    {
        // ===== FACULTY GROUP =====
        $facultyGroup = AcademicMenuGroup::updateOrCreate(
            ['slug' => 'faculty'],
            [
                'title'    => 'Faculty',
                'position' => 1,
                'is_active'=> true,
            ]
        );

        // ===== INSTITUTE GROUP =====
        $instituteGroup = AcademicMenuGroup::updateOrCreate(
            ['slug' => 'institute'],
            [
                'title'    => 'Institute',
                'position' => 2,
                'is_active'=> true,
            ]
        );

        /*
         * Helper to create a unit, its departments, config, and (optionally) staff
         */
        $createFaculty = function (AcademicMenuGroup $group, array $data) {
            $unit = AcademicUnit::updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'academic_menu_group_id'     => $group->id,
                    'icon'                       => $data['icon'] ?? null,
                    'name'                       => $data['title'],
                    'short_name'                 => $data['short_name'] ?? null,
                    'short_description'          => $data['short_description'] ?? null,
                    'button_name'                => $data['button_name'] ?? null,
                    'menu_order'                 => $data['position'] ?? 0,
                    'base_url'                   => $data['base_url'] ?? '/' . $data['slug'],
                    'home_layout'                => $data['home_layout'] ?? 'faculty_home',
                    'home_has_hero'              => $data['home_has_hero'] ?? true,
                    'home_has_department_grid'   => $data['home_has_department_grid'] ?? true,
                    'config'                     => $data['config'] ?? null,
                    'status'                     => 'published',
                ]
            );

            // ==========================
            // DEPARTMENTS
            // ==========================
            $position = 1;
            foreach ($data['departments'] as $dept) {
                AcademicUnitDepartment::updateOrCreate(
                    [
                        'academic_unit_id' => $unit->id,
                        'short_code'       => $dept['short_code'],
                    ],
                    [
                        'title'      => $dept['title'],
                        'position'   => $position++,
                    ]
                );
            }

            // cache departments keyed by short_code for staff creation
            $departmentsByCode = $unit->departments()->get()->keyBy('short_code');

            // ==========================
            // STAFF (optional – only VABS currently)
            // ==========================
            if (!empty($data['staff'])) {
                foreach ($data['staff'] as $deptShortCode => $sections) {
                    /** @var \App\Models\AcademicUnitDepartment|null $department */
                    $department = $departmentsByCode->get($deptShortCode);

                    if (!$department) {
                        continue;
                    }

                    $secPos = 1;
                    foreach ($sections as $sectionData) {
                        $section = AcademicUnitStaffSection::updateOrCreate(
                            [
                                'academic_unit_id' => $unit->id,
                                'department_id'    => $department->id,
                                'title'            => $sectionData['title'],
                            ],
                            [
                                'position' => $secPos++,
                            ]
                        );

                        $memPos = 1;
                        foreach ($sectionData['members'] as $memberData) {
                            AcademicUnitStaffMember::updateOrCreate(
                                [
                                    'staff_section_id' => $section->id,
                                    'email'            => $memberData['email'] ?? null,
                                    'name'             => $memberData['name'],
                                ],
                                [
                                    'designation' => $memberData['designation'] ?? null,
                                    'phone'       => $memberData['phone'] ?? null,
                                    'image_path'  => $memberData['image_path'] ?? null,
                                    'position'    => $memPos++,
                                    // JSON links array on the member itself
                                    'links'       => $memberData['links'] ?? [],
                                ]
                            );
                        }
                    }
                }
            }
        };

        // ========= DATA PER FACULTY =========

        // VABS
        $createFaculty($facultyGroup, [
            'icon'         => 'fa-solid fa-circle-info',
            'title'        => 'Veterinary, Animal and Biomedical Sciences',
            'slug'         => 'vabs',
            'short_name'   => 'VABS',
            'short_description' => 'Faculty focused on veterinary medicine, animal health and biomedical sciences with DVM program and 15 specialized departments.',
            'button_name'  => 'Go To VABS',
            'position'     => 1,
            'base_url'     => '/vabs',
            'config'       => [
                'home' => [
                    'endpoint'            => '/',
                    'layout'              => 'faculty_home',
                    'has_hero'            => true,
                    'has_department_grid' => true,
                ],
                'about' => [
                    'endpoint'      => '/about-vabs',
                    'section_title' => 'About VABS',
                ],
                'departments' => [
                    'endpoint' => '/departments',
                ],
                'facilities' => [
                    'endpoint'      => '/facilities',
                    'section_title' => 'Facilities',
                ],
                'faculty_members' => [
                    'endpoint' => '/faculty-member',
                ],
                'academic' => [
                    'endpoint'   => '/academic',
                    'menu_label' => 'Academic',
                    'sub_pages'  => [
                        'Academic Program',
                        'Syllabus',
                        'Academic Calendar',
                        'Class Routine',
                        'Result',
                    ],
                ],
                'research' => [
                    'endpoint'   => '/research',
                    'menu_label' => 'Research',
                ],
            ],
            'departments' => [
                ['title' => 'Anatomy and Histology', 'short_code' => 'VAH'],
                ['title' => 'Physiology', 'short_code' => 'VPH'],
                ['title' => 'Pharmacology and Toxicology', 'short_code' => 'VPT'],
                ['title' => 'Microbiology and Public Health', 'short_code' => 'MPH'],
                ['title' => 'Livestock Production and Management', 'short_code' => 'LPM'],
                ['title' => 'Pathology', 'short_code' => 'PAT'],
                ['title' => 'Parasitology', 'short_code' => 'PAR'],
                ['title' => 'Genetics and Animal Breeding', 'short_code' => 'GAB'],
                ['title' => 'Dairy Science', 'short_code' => 'DSC'],
                ['title' => 'Poultry Science', 'short_code' => 'PSC'],
                ['title' => 'Epidemiology and Preventive Medicine', 'short_code' => 'EPM'],
                ['title' => 'Animal Nutrition', 'short_code' => 'ANT'],
                ['title' => 'Medicine', 'short_code' => 'MED'],
                ['title' => 'Surgery', 'short_code' => 'SGR'],
                ['title' => 'Theriogenology', 'short_code' => 'THE'],
            ],
            'staff' => [
                'VAH' => [
                    [
                        'title'   => 'Vice-Chancellor',
                        'members' => [
                            [
                                'name'        => 'Prof. Dr. Md. Nazmul Ahsan',
                                'designation' => 'Vice-Chancellor',
                                'email'       => 'vc@kau.ac.bd',
                                'phone'       => null,
                                'image_path'  => 'members/7bsdUYWlQo1wyAGQBRziwdaseOaD3ZgfgAppZDKT.png',
                                'links'       => [
                                    [
                                        'icon' => 'fa-solid fa-google-scholar',
                                        'url'  => 'https://admin.kau.khandkershahed.com/storage/members/7bsdUYWlQo1wyAGQBRziwdaseOaD3ZgfgAppZDKT.png',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'title'   => 'Officers',
                        'members' => [
                            [
                                'name'        => 'Delwar Hossain',
                                'designation' => 'Ps To Vc (In charge)',
                                'email'       => 'delwar@kau.ac.bd',
                                'phone'       => '+880 1521 576582',
                                'links'       => [],
                            ],
                            [
                                'name'        => 'Md. Murad Hossain',
                                'designation' => 'Administrative Officer',
                                'email'       => 'kaumurad@gmail.com',
                                'phone'       => '+880 1629 964723',
                                'links'       => [],
                            ],
                            [
                                'name'        => 'Md. Abid Hossain',
                                'designation' => 'Administrative Officer',
                                'email'       => 'hossainmdabid007@gmail.com',
                                'phone'       => '+880 1833 293355',
                                'links'       => [],
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        // AG
        $createFaculty($facultyGroup, [
            'icon'         => 'fa-solid fa-leaf',
            'title'        => 'Agriculture',
            'slug'         => 'ag',
            'short_name'   => 'AG',
            'short_description' => 'Second-largest faculty offering BSc Ag (Hons) with 11 departments covering crop, soil, plant, extension and agroforestry sciences.',
            'button_name'  => 'Go To AG',
            'position'     => 2,
            'base_url'     => '/ag',
            'config'       => [
                'home' => [
                    'endpoint'            => '/',
                    'layout'              => 'faculty_home',
                    'has_hero'            => true,
                    'has_department_grid' => true,
                ],
                'about' => [
                    'endpoint'      => '/about-ag',
                    'section_title' => 'About AG',
                ],
                'departments' => ['endpoint' => '/departments'],
                'facilities'  => [
                    'endpoint'      => '/facilities',
                    'section_title' => 'Facilities',
                ],
                'faculty_members' => ['endpoint' => '/faculty-member'],
                'academic' => [
                    'endpoint'   => '/academic',
                    'menu_label' => 'Academic',
                    'sub_pages'  => [
                        'Academic Program',
                        'Syllabus',
                        'Academic Calendar',
                        'Class Routine',
                        'Result',
                    ],
                ],
                'research' => [
                    'endpoint'   => '/research',
                    'menu_label' => 'Research',
                ],
            ],
            'departments' => [
                ['title' => 'Agronomy', 'short_code' => 'AGM'],
                ['title' => 'Soil Science', 'short_code' => 'SOS'],
                ['title' => 'Entomology', 'short_code' => 'ENT'],
                ['title' => 'Horticulture', 'short_code' => 'HRT'],
                ['title' => 'Plant Pathology', 'short_code' => 'PP'],
                ['title' => 'Crop Botany', 'short_code' => 'CB'],
                ['title' => 'Plant Genetics and Biotechnology', 'short_code' => 'PGB'],
                ['title' => 'Agricultural Extension and Information Systems', 'short_code' => 'AEIS'],
                ['title' => 'Agroforestry', 'short_code' => 'AF'],
                ['title' => 'Agricultural Chemistry', 'short_code' => 'ACH'],
                ['title' => 'Biochemistry and Molecular Biology', 'short_code' => 'BMB'],
            ],
            'staff' => [],
        ]);

        // FOS
        $createFaculty($facultyGroup, [
            'icon'         => 'fa-solid fa-fish',
            'title'        => 'Fisheries & Ocean Sciences',
            'slug'         => 'fos',
            'short_name'   => 'FOS',
            'short_description' => 'Faculty established in 2019 offering BSc in Fisheries (Honours) and research on inland and marine resources.',
            'button_name'  => 'Go To FOS',
            'position'     => 3,
            'base_url'     => '/fos',
            'config'       => [
                'home' => [
                    'endpoint'            => '/',
                    'layout'              => 'faculty_home',
                    'has_hero'            => true,
                    'has_department_grid' => true,
                ],
                'about' => [
                    'endpoint'      => '/about-fos',
                    'section_title' => 'About FOS',
                ],
                'departments' => ['endpoint' => '/departments'],
                'facilities'  => [
                    'endpoint'      => '/facilities',
                    'section_title' => 'Facilities',
                ],
                'faculty_members' => ['endpoint' => '/faculty-member'],
                'academic' => [
                    'endpoint'   => '/academic',
                    'menu_label' => 'Academic',
                    'sub_pages'  => [
                        'Academic Program',
                        'Syllabus',
                        'Academic Calendar',
                        'Class Routine',
                        'Result',
                    ],
                ],
                'research' => [
                    'endpoint'   => '/research',
                    'menu_label' => 'Research',
                ],
            ],
            'departments' => [
                ['title' => 'Fishery Biology and Genetics', 'short_code' => 'FBG'],
                ['title' => 'Aquaculture', 'short_code' => 'AQ'],
                ['title' => 'Fishery Resources Conservation and Management', 'short_code' => 'FRCM'],
                ['title' => 'Fisheries Technology and Quality Control', 'short_code' => 'FTQC'],
                ['title' => 'Oceanography', 'short_code' => 'OC'],
                ['title' => 'Fish Health Management', 'short_code' => 'FHM'],
            ],
            'staff' => [],
        ]);

        // AEAS
        $createFaculty($facultyGroup, [
            'icon'         => 'fa-solid fa-chart-line',
            'title'        => 'Agricultural Economics & Agribusiness Studies',
            'slug'         => 'aeas',
            'short_name'   => 'AEAS',
            'short_description' => 'Faculty covering agricultural economics, rural development, statistics, finance and agribusiness for modern agri-food systems.',
            'button_name'  => 'Go To AEAS',
            'position'     => 4,
            'base_url'     => '/aeas',
            'config'       => [
                'home' => [
                    'endpoint'            => '/',
                    'layout'              => 'faculty_home',
                    'has_hero'            => true,
                    'has_department_grid' => true,
                ],
                'about' => [
                    'endpoint'      => '/about-aeas',
                    'section_title' => 'About AEAS',
                ],
                'departments' => ['endpoint' => '/departments'],
                'facilities'  => [
                    'endpoint'      => '/facilities',
                    'section_title' => 'Facilities',
                ],
                'faculty_members' => ['endpoint' => '/faculty-member'],
                'academic' => [
                    'endpoint'   => '/academic',
                    'menu_label' => 'Academic',
                    'sub_pages'  => [
                        'Academic Program',
                        'Syllabus',
                        'Academic Calendar',
                        'Class Routine',
                        'Result',
                    ],
                ],
                'research' => [
                    'endpoint'   => '/research',
                    'menu_label' => 'Research',
                ],
            ],
            'departments' => [
                ['title' => 'Agricultural Economics', 'short_code' => 'AE'],
                ['title' => 'Sociology and Rural Development', 'short_code' => 'SRD'],
                ['title' => 'Agribusiness and Marketing', 'short_code' => 'AM'],
                ['title' => 'Agricultural Statistics and Bioinformatics', 'short_code' => 'ASB'],
                ['title' => 'Agricultural Finance, Co-operative and Banking', 'short_code' => 'AFCB'],
                ['title' => 'Language and Communication Studies', 'short_code' => 'LCS'],
            ],
            'staff' => [],
        ]);

        // AET
        $createFaculty($facultyGroup, [
            'icon'         => 'fa-solid fa-gears',
            'title'        => 'Agricultural Engineering & Technology',
            'slug'         => 'aet',
            'short_name'   => 'AET',
            'short_description' => 'Engineering-focused faculty with programs in structures, machinery, water management, computing and physical sciences for agriculture.',
            'button_name'  => 'Go To AET',
            'position'     => 5,
            'base_url'     => '/aet',
            'config'       => [
                'home' => [
                    'endpoint'            => '/',
                    'layout'              => 'faculty_home',
                    'has_hero'            => true,
                    'has_department_grid' => true,
                ],
                'about' => [
                    'endpoint'      => '/about-aet',
                    'section_title' => 'About AET',
                ],
                'departments' => ['endpoint' => '/departments'],
                'facilities'  => [
                    'endpoint'      => '/facilities',
                    'section_title' => 'Facilities',
                ],
                'faculty_members' => ['endpoint' => '/faculty-member'],
                'academic' => [
                    'endpoint'   => '/academic',
                    'menu_label' => 'Academic',
                    'sub_pages'  => [
                        'Academic Program',
                        'Syllabus',
                        'Academic Calendar',
                        'Class Routine',
                        'Result',
                    ],
                ],
                'research' => [
                    'endpoint'   => '/research',
                    'menu_label' => 'Research',
                ],
            ],
            'departments' => [
                ['title' => 'Farm Structure', 'short_code' => 'FS'],
                ['title' => 'Farm Power and Machinery', 'short_code' => 'FPM'],
                ['title' => 'Irrigation and Water Management', 'short_code' => 'IWM'],
                ['title' => 'Computer Science and Engineering', 'short_code' => 'CSE'],
                ['title' => 'Mathematics and Physics', 'short_code' => 'MP'],
            ],
            'staff' => [],
        ]);

        // GTI (Institute)
        $createFaculty($instituteGroup, [
            'icon'         => 'fa-solid fa-graduation-cap',
            'title'        => 'Graduate Training Institute',
            'slug'         => 'gti',
            'short_name'   => 'GTI',
            'short_description' => 'Central institute for graduate-level training, short courses and professional development within KAU.',
            'button_name'  => 'Go To GTI',
            'position'     => 1,
            'base_url'     => 'https://kau.ac.bd',
            'home_layout'  => 'institute_home',
            'home_has_department_grid' => false,
            'config'       => [
                'home' => [
                    'endpoint'            => '/graduate-training-institute',
                    'layout'              => 'institute_home',
                    'has_hero'            => true,
                    'has_department_grid' => false,
                ],
                'about' => [
                    'endpoint'      => '/graduate-training-institute#about',
                    'section_title' => 'About GTI',
                ],
                'programs' => [
                    'endpoint' => '/graduate-training-institute#programs',
                    'types' => [
                        'Short Courses',
                        'Workshops',
                        'Professional Training',
                    ],
                ],
                'facilities' => [
                    'endpoint'      => '/graduate-training-institute#facilities',
                    'section_title' => 'Facilities',
                ],
                'contact' => [
                    'endpoint'      => '/graduate-training-institute#contact',
                    'section_title' => 'Contact Information',
                ],
            ],
            'departments' => [], // institute-level only
            'staff'       => [],
        ]);
    }
}
