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
        'title' => 'Banner',
        'icon' => 'fa-solid fa-layer-group fs-3',
        'routes' => [
            'admin.banner.index',
            'admin.banner.create',
            'admin.banner.edit',
        ],
        'subMenu' => [
            [
                'title' => 'Banner',
                'permission' => 'view banner',
                'routes' => ['admin.banner.index', 'admin.banner.create', 'admin.banner.edit'],
                'route' => 'admin.banner.index',
            ],
        ],
    ],
    [
        'title' => 'Notice Management',
        'routes' => ['admin.notice-category.index','admin.notice.index', 'admin.notice.create', 'admin.notice.edit'],
        'subMenu' => [

            [
                'title' => 'Notice Category',
                'permission' => 'view notice category',
                'routes' => ['admin.notice-category.index'],
                'route' => 'admin.notice-category.index',
            ],
            [
                'title' => 'Notice',
                'permission' => 'view notice',
                'routes' => ['admin.notice.index', 'admin.notice.create', 'admin.notice.edit'],
                'route' => 'admin.notice.index',
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
        'routes' => ['admin.user.index', 'admin.contact.index', 'admin.subscription.index'],
        'subMenu' => [
            [
                'title' => 'User List',
                'permission' => 'view user',
                'routes' => ['admin.user.index', 'admin.user.create', 'admin.user.edit'],
                'route' => 'admin.user.index',
            ],
            [
                'title' => 'Contact',
                'permission' => 'view contact',
                'routes' => ['admin.contact.index', 'admin.contact.create', 'admin.contact.edit'],
                'route' => 'admin.contact.index',
            ],
            [
                'title' => 'Subscription',
                'permission' => 'view subscription',
                'routes' => ['admin.subscription.index', 'admin.subscription.create', 'admin.subscription.edit'],
                'route' => 'admin.subscription.index',
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
        'routes' => ['admin.settings.index', 'admin.faq.index', 'admin.terms.index', 'admin.privacy.index'],
        'subMenu' => [
            [
                'title' => 'Setting',
                'permission' => 'view setting',
                'routes' => ['admin.settings.index'],
                'route' => 'admin.settings.index',
            ],
            [
                'title' => 'FAQs',
                'permission' => 'view faq',
                'routes' => ['admin.faq.index', 'admin.faq.create', 'admin.faq.edit'],
                'route' => 'admin.faq.index',
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
