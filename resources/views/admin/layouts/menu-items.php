<?php

return [
    [
        'title' => 'Dashboard',
        'icon' => 'fa-solid fa-gauge fs-3',
        'permission' => 'view dashboard',
        'routes' => ['admin.dashboard'],
        'route' => 'admin.dashboard',
    ],
    [
        'type' => 'heading',
        'title' => 'CMS',
    ],

    [
        'title' => 'Home Page',
        'icon' => 'fa-solid fa-house fs-3',
        'routes' => [
            'admin.homepage.builder.edit',
            'admin.home_popups.index',
        ],
        'subMenu' => [
            [
                'title'      => 'Home Page',
                'permission' => 'manage homepage',
                'routes'     => [
                    'admin.homepage.builder.edit',
                ],
                'route'      => 'admin.homepage.builder.edit',
            ],
            [
                'title'      => 'Home Popups',
                'permission' => 'manage home popup',
                'routes'     => [
                    'admin.home_popups.index',
                ],
                'route'      => 'admin.home_popups.index',
            ],
        ],
    ],

    [
        'title' => 'About Pages',
        'icon'  => 'fa-solid fa-circle-info fs-3',
        'permission' => 'view about page',
        'routes' => [
            'admin.about.index',
            'admin.about.create',
            'admin.about.edit',
        ],
        'route' => 'admin.about.index',
    ],

    [
        'title' => 'Notice Management',
        'icon'  => 'fa-solid fa-bullhorn fs-3',
        'routes' => [
            'admin.notice-category.index',
            'admin.notice.index',
            'admin.notice.create',
            'admin.notice.edit',
        ],
        'route'      => 'admin.notice.index',
        // 'subMenu' => [
        //     [
        //         'title'      => 'Notice Categories',
        //         'permission' => 'view notice category',
        //         'routes'     => ['admin.notice-category.index'],
        //         'route'      => 'admin.notice-category.index',
        //     ],
        //     [
        //         'title'      => 'Notices',
        //         'permission' => 'view notice',
        //         'routes'     => ['admin.notice.index', 'admin.notice.create', 'admin.notice.edit'],
        //         'route'      => 'admin.notice.index',
        //     ],
        // ],
    ],

    [
        'title' => 'News Management',
        'icon'  => 'fa-solid fa-newspaper fs-3',
        'permission' => 'view news',
        'routes' => ['admin.news.index', 'admin.news.create', 'admin.news.edit'],
        'route' => 'admin.news.index',
    ],


    // |--------------------------------------------------------------
    // | NOTE (Future steps)
    // |--------------------------------------------------------------
    // | Events, Tenders and Galleries controllers/views exist in your
    // | project, but the admin routes are planned to be added in:
    // |   - Step 7: Gallery Integration (admin routes + frontend binding)
    // |   - Step 8: Public/Admin APIs for Events/Tenders
    // |
    // | After those steps, we will uncomment these menu items.

    [
        'title' => 'Events',
        'icon'  => 'fa-solid fa-calendar-days fs-3',
        'permission' => 'view events',
        'routes' => ['admin.events.index', 'admin.events.create', 'admin.events.edit'],
        'route' => 'admin.events.index',
    ],
    [
        'title' => 'Tenders',
        'icon'  => 'fa-solid fa-file-contract fs-3',
        'permission' => 'view tenders',
        'routes' => ['admin.tenders.index', 'admin.tenders.create', 'admin.tenders.edit'],
        'route' => 'admin.tenders.index',
    ],
    [
        'title' => 'Galleries',
        'icon'  => 'fa-solid fa-images fs-3',
        'permission' => 'view gallery',
        'routes' => ['admin.galleries.index', 'admin.galleries.create', 'admin.galleries.edit'],
        'route' => 'admin.galleries.index',
    ],


    [
        'title' => 'Administration',
        'icon'  => 'fa-solid fa-sitemap fs-3',
        'routes' => [
            // Structure
            'admin.administration.index',
            'admin.administration.group.create',
            'admin.administration.group.edit',
            'admin.administration.office.create',
            'admin.administration.office.edit',
            'admin.administration.office.page',
            'admin.administration.section.create',
            'admin.administration.section.edit',
            'admin.administration.member.create',
            'admin.administration.member.edit',
            // Office CMS
            'admin.administration.office.cms.dashboard',
            'admin.administration.office.cms.pages.index',
            'admin.administration.office.cms.pages.create',
            'admin.administration.office.cms.pages.edit',
            'admin.administration.office.cms.menu.index',
            'admin.administration.office.cms.menu.create',
            'admin.administration.office.cms.menu.edit',
        ],
        'subMenu' => [
            [
                'title'      => 'Administration Structure',
                'permission' => 'view admin office',
                'routes'     => [
                    'admin.administration.index',
                    'admin.administration.group.create',
                    'admin.administration.group.edit',
                    'admin.administration.office.create',
                    'admin.administration.office.edit',
                    'admin.administration.office.page',
                    'admin.administration.section.create',
                    'admin.administration.section.edit',
                    'admin.administration.member.create',
                    'admin.administration.member.edit',
                ],
                'route'      => 'admin.administration.index',
            ],
            [
                'title'      => 'Office CMS (Pages & Menu)',
                'permission' => 'view admin section',
                'routes'     => [
                    'admin.administration.office.cms.dashboard',
                    'admin.administration.office.cms.pages.index',
                    'admin.administration.office.cms.pages.create',
                    'admin.administration.office.cms.pages.edit',
                    'admin.administration.office.cms.menu.index',
                    'admin.administration.office.cms.menu.create',
                    'admin.administration.office.cms.menu.edit',
                ],
                // Office CMS routes need {slug}. Link to structure index so admin can pick an office.
                'route'      => 'admin.administration.index',
            ],
        ],
    ],

    [
        'title' => 'Academic Module',
        'icon' => 'fa-solid fa-graduation-cap fs-3',
        'routes' => [
            'admin.academic.sites.index',
            'admin.academic.pages.index',
            'admin.academic.pages.create',
            'admin.academic.pages.edit',
            'admin.academic.staff.index',
            'admin.academic.staff.finder',
            'admin.academic.*'
        ],
        'subMenu' => [
            [
                'title' => 'Sites & Menus',
                'permission' => 'view academic sites',
                'routes' => [
                    'admin.academic.sites.index',
                ],
                'route' => 'admin.academic.sites.index',
            ],
            [
                'title' => 'Pages',
                'permission' => 'view academic pages',
                'routes' => [
                    'admin.academic.pages.index',
                    'admin.academic.pages.create',
                    'admin.academic.pages.edit',
                ],
                'route' => 'admin.academic.pages.index',
            ],
            [
                'title' => 'Departments & Staff',
                'permission' => 'view academic departments',
                'routes' => [
                    'admin.academic.staff.*',
                    'admin.academic.publications.*',
                    'admin.academic.staff.index',
                ],
                'route' => 'admin.academic.staff.index',
            ],
            [
                'title' => 'Academic Member Finder',
                'permission' => 'view academic staff',
                'routes' => [
                    // 'admin.academic.staff.*',
                    // 'admin.academic.publications.*',
                    'admin.academic.staff.finder',
                ],
                'route' => 'admin.academic.staff.finder',
            ],
        ],
    ],

    [
        'title' => 'Admission Panel',
        'icon'  => 'fa-solid fa-user-graduate fs-3',
        'routes' => [
            'admin.admission.index',
            'admin.admission.create',
            'admin.admission.edit',
        ],
        'route' => 'admin.admission.index',
    ],

    [
        'title' => 'Main CMS',
        'icon'  => 'fa-solid fa-layer-group fs-3',
        'routes' => [
            'admin.cms.main.menu.index',
            'admin.cms.main.menu.create',
            'admin.cms.main.menu.edit',

            'admin.cms.main.pages.index',
            'admin.cms.main.pages.create',
            'admin.cms.main.pages.edit',

            // galleries already exist in your app
            'admin.galleries.index',
            'admin.galleries.create',
            'admin.galleries.edit',
        ],
        'subMenu' => [
            [
                'title' => 'Main Menu Builder',
                'icon'  => 'fa-solid fa-bars fs-3',
                'route' => 'admin.cms.main.menu.index',
                'permission' => 'view academic sites',
            ],
            [
                'title' => 'Main Page Builder',
                'icon'  => 'fa-solid fa-file-lines fs-3',
                'route' => 'admin.cms.main.pages.index',
                'permission' => 'view academic pages',
            ],
            [
                'title' => 'Galleries',
                'icon'  => 'fa-solid fa-images fs-3',
                'route' => 'admin.galleries.index',
                'permission' => 'view galleries',
            ],
        ],
    ],



    [
        'type' => 'heading',
        'title' => 'CRM & User Management',
    ],

    [
        'title' => 'CRM & User Management',
        'icon' => 'fa-solid fa-users fs-3',
        'routes' => [
            'admin.user.index',
            'admin.contact.index',
            'admin.subscription.index',
            'admin.faq.index',
            'admin.faq.create',
            'admin.faq.edit'
        ],
        'subMenu' => [
            [
                'title' => 'FAQs',
                'permission' => 'view faq',
                'routes' => ['admin.faq.index', 'admin.faq.create', 'admin.faq.edit'],
                'route' => 'admin.faq.index',
            ],
            [
                'title' => 'Contact',
                'permission' => 'view contact',
                'routes' => ['admin.contact.index', 'admin.contact.create', 'admin.contact.edit'],
                'route' => 'admin.contact.index',
            ],
        ],
    ],

    [
        'type' => 'heading',
        'title' => 'Staff Management',
    ],

    [
        'title' => 'Staff Management',
        'icon' => 'fa-solid fa-user-shield fs-3',
        'routes' => [
            'admin.staff.index',
            'admin.staff.create',
            'admin.staff.edit',
            'admin.roles.index',
            'admin.roles.create',
            'admin.roles.edit',
            'admin.permission.index',
            'admin.permission.create',
            'admin.permission.edit',
        ],
        'subMenu' => [
            [
                'title' => 'Staff / Admins',
                'permission' => 'view staff',
                'routes' => ['admin.staff.index', 'admin.staff.create', 'admin.staff.edit'],
                'route' => 'admin.staff.index',
            ],
            [
                'title' => 'Roles',
                'permission' => 'view role',
                'routes' => ['admin.roles.index', 'admin.roles.create', 'admin.roles.edit'],
                'route' => 'admin.roles.index',
            ],
            [
                'title' => 'Permissions',
                'permission' => 'view permission',
                'routes' => ['admin.permission.index', 'admin.permission.create', 'admin.permission.edit'],
                'route' => 'admin.permission.index',
            ],
        ],
    ],

    [
        'type' => 'heading',
        'title' => 'Settings',
    ],

    [
        'title' => 'Web Settings',
        'icon' => 'fa-solid fa-gear fs-3',
        'routes' => ['admin.settings.index', 'admin.terms.index', 'admin.privacy.index'],
        'subMenu' => [
            [
                'title' => 'Setting',
                'permission' => 'view setting',
                'routes' => ['admin.settings.index'],
                'route' => 'admin.settings.index',
            ],
            [
                'title' => 'Footer Section',
                'permission' => 'view footer',
                'routes' => ['admin.footer.index'],
                'route' => 'admin.footer.index',
            ],
            [
                'title' => 'Term & Condition',
                'permission' => 'view terms',
                'routes' => ['admin.terms.index', 'admin.terms.create', 'admin.terms.edit'],
                'route' => 'admin.terms.index',
            ],
            [
                'title' => 'Privacy Policy',
                'permission' => 'view privacy',
                'routes' => ['admin.privacy.index', 'admin.privacy.create', 'admin.privacy.edit'],
                'route' => 'admin.privacy.index',
            ],
        ],
    ],
];
