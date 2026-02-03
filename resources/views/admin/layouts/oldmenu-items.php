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
        'routes' => ['admin.notice-category.index', 'admin.notice.index', 'admin.notice.create', 'admin.notice.edit'],
        'route'      => 'admin.notice.index',
        // 'subMenu' => [
        //     [
        //         'title'      => 'Manage Notices',
        //         'permission' => 'view notice',
        //         'routes'     => [
        //             'admin.notice.index',
        //             'admin.notice.create',
        //             'admin.notice.edit',
        //         ],
        //         'route'      => 'admin.notice.index',
        //     ],
        // ],
    ],
    [
        'title' => 'News Management',
        'icon'  => 'fa-solid fa-newspaper fs-3',
        'routes' => ['admin.news.index', 'admin.news.create', 'admin.news.edit'],
        'route' => 'admin.news.index',
        // 'subMenu' => [
        //     [
        //         'title' => 'All News',
        //         'permission' => 'view news',
        //         'routes' => ['admin.news.index', 'admin.news.create', 'admin.news.edit'],
        //         'route' => 'admin.news.index',
        //     ],
        // ],
    ],

    [
        'title' => 'Administration Panel',
        'icon'  => 'fa-solid fa-sitemap fs-3',
        'routes' => [
            'admin.administration.index',
            'admin.administration.office.page',
        ],
        'route' => 'admin.administration.index',
        // 'subMenu' => [

        //     [
        //         'title'      => 'Administration Sections',
        //         'permission' => 'view admin section',
        //         'routes' => [
        //             'admin.administration.index',
        //         ],
        //         'route' => 'admin.administration.index',
        //     ],
        //     [
        //         'title'      => 'Office CMS Dashboard',
        //         'permission' => 'view admin section',
        //         'routes' => [
        //             'admin.administration.office.cms.menu.index',
        //             'admin.administration.office.cms.menu.create',
        //             'admin.administration.office.cms.menu.edit',
        //             'admin.administration.office.cms.pages.index',
        //             'admin.administration.office.cms.pages.create',
        //             'admin.administration.office.cms.pages.edit',
        //             'admin.administration.office.cms.dashboard',
        //         ],
        //         'route' => 'admin.administration.office.cms.dashboard',
        //     ],
        //     [
        //         'title'      => 'Office Menus',
        //         'permission' => 'view admin section',
        //         'routes' => [
        //             'admin.administration.office.cms.menu.index',
        //             'admin.administration.office.cms.menu.create',
        //             'admin.administration.office.cms.menu.edit',
        //         ],
        //         'route' => 'admin.administration.office.cms.menu.index',
        //     ],
        //     [
        //         'title'      => 'Administration Offices',
        //         'permission' => 'view admin office',
        //         'routes' => [
        //             'admin.administration.index',
        //         ],
        //         'route' => 'admin.administration.index',
        //     ],

        // ],
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
                    'admin.academic.staff.index',
                ],
                'route' => 'admin.academic.staff.index',
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
        // 'subMenu' => [

        //     [
        //         'title'      => 'Admission Menu & Pages',
        //         'permission' => 'view admission',
        //         'routes' => [
        //             'admin.admission.index',
        //             'admin.admission.create',
        //             'admin.admission.edit',
        //         ],
        //         'route' => 'admin.admission.index',
        //     ],

        // ],
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
            // [
            //     'title' => 'User List',
            //     'permission' => 'view user',
            //     'routes' => ['admin.user.index', 'admin.user.create', 'admin.user.edit'],
            //     'route' => 'admin.user.index',
            // ],

            // [
            //     'title' => 'Subscription',
            //     'permission' => 'view subscription',
            //     'routes' => ['admin.subscription.index', 'admin.subscription.create', 'admin.subscription.edit'],
            //     'route' => 'admin.subscription.index',
            // ],
        ],
    ],

    [
        'type' => 'heading',
        'title' => 'Staff Management',
    ],

    [
        'title' => 'Staff Management',
        'icon' => 'fa-solid fa-user-shield fs-3',
        'routes' => ['admin.staff.index', 'admin.roles.index'],
        'subMenu' => [
            [
                'title' => 'Staff',
                'permission' => 'view staff',
                'routes' => ['admin.staff.index', 'admin.staff.create', 'admin.staff.edit'],
                'route' => 'admin.staff.index',
            ],
            [
                'title' => 'Roles & Permission',
                'permission' => 'view role',
                'routes' => ['admin.roles.index', 'admin.roles.create', 'admin.roles.edit'],
                'route' => 'admin.roles.index',
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
