@php
    // This is the dynamic menu structure from your original file
    $menuItems = [
        //====================== Event Management Start ============
        //====================== Event Management End ==============
        //====================== Heading ===================
        [
            'title' => 'CMS',
        ],
        //====================== Frontend Management Start ============
        [
            'title' => 'Frontend Management',
            'icon' => 'ki-filled ki-element-11', // Replaced empty SVG with a relevant icon
            'routes' => [
                'admin.banner.index',
                'admin.banner.create',
                'admin.banner.edit',
                'admin.blog-category.index',
                'admin.blog-category.create',
                'admin.blog-category.edit',
                'admin.blog-post.index',
                'admin.blog-post.create',
            ],
            'subMenu' => [
                [
                    'title' => 'Banner',
                    'routes' => ['admin.banner.index', 'admin.banner.create', 'admin.banner.edit'],
                    'route' => 'admin.banner.index',
                ],
                [
                    'title' => 'Blog Category',
                    'routes' => ['admin.blog-category.index', 'admin.blog-category.create', 'admin.blog-category.edit'],
                    'route' => 'admin.blog-category.index',
                ],
                [
                    'title' => 'Blog',
                    'routes' => ['admin.blog-post.index', 'admin.blog-post.create', 'admin.blog-post.edit'],
                    'route' => 'admin.blog-post.index',
                ],
            ],
        ],
        //====================== Frontend Management End ==============
        //====================== Heading ===================
        [
            'title' => 'Settings',
        ],
        // ========================= Setting Start ====================
        [
            'title' => 'Web Settings',
            'icon' => 'ki-filled ki-setting-2', // Replaced empty SVG with a relevant icon
            'routes' => [
                'admin.settings.index',
                'admin.faq.index',
                'admin.faq.create',
                'admin.faq.edit',
                'admin.terms.index',
                'admin.terms.create',
                'admin.terms.edit',
                'admin.privacy.index',
                'admin.privacy.create',
                'admin.privacy.edit',
            ],
            'subMenu' => [
                [
                    'title' => 'Setting',
                    'routes' => ['admin.settings.index'],
                    'route' => 'admin.settings.index',
                ],
                [
                    'title' => 'FAQs',
                    'routes' => ['admin.faq.index', 'admin.faq.create', 'admin.faq.edit'],
                    'route' => 'admin.faq.index',
                ],
                [
                    'title' => 'Term & Condition',
                    'routes' => ['admin.terms.index', 'admin.terms.create', 'admin.terms.edit'],
                    'route' => 'admin.terms.index',
                ],
                [
                    'title' => 'Privacy Policy',
                    'routes' => ['admin.privacy.index', 'admin.privacy.create', 'admin.privacy.edit'],
                    'route' => 'admin.privacy.index',
                ],
            ],
        ],
        // ========================= Setting End ======================
        //====================== Heading ===================
        [
            'title' => 'CRM & User Management',
        ],
        // =================== Management Section Start ===============
        [
            'title' => 'CRM & User Management',
            'icon' => 'ki-filled ki-profile-circle', // Replaced empty SVG with a relevant icon
            'routes' => [
                'admin.user.index',
                'admin.user.create',
                'admin.user.edit',
                'admin.subscription.index',
                'admin.subscription.create',
                'admin.subscription.edit',
                'admin.contact.index',
                'admin.contact.create',
                'admin.contact.edit',
            ],
            'subMenu' => [
                [
                    'title' => 'User List',
                    'routes' => ['admin.user.index', 'admin.user.create', 'admin.user.edit'],
                    'route' => 'admin.user.index',
                ],
                [
                    'title' => 'Contact',
                    'routes' => ['admin.contact.index', 'admin.contact.create', 'admin.contact.edit'],
                    'route' => 'admin.contact.index',
                ],
                [
                    'title' => 'Subscription',
                    'routes' => ['admin.subscription.index', 'admin.subscription.create', 'admin.subscription.edit'],
                    'route' => 'admin.subscription.index',
                ],
            ],
        ],
        [
            'title' => 'Staff Management',
            'icon' => 'ki-filled ki-profile-circle', // Replaced empty SVG with a relevant icon
            'routes' => ['admin.staff.index', 'admin.staff.create', 'admin.staff.edit'],
            'subMenu' => [
                [
                    'title' => 'Staff',
                    'routes' => ['admin.staff.index', 'admin.staff.create', 'admin.staff.edit'],
                    'route' => 'admin.staff.index',
                ],

                [
                    'title' => 'Roles',
                    'routes' => ['admin.roles.index', 'admin.roles.create', 'admin.roles.edit'],
                    'route' => 'admin.roles.index',
                ],
            ],
        ],
        // =================== Management Section End =================
    ];
@endphp

<div class="kt-sidebar bg-background border-e border-e-border fixed top-0 bottom-0 z-20 hidden lg:flex flex-col items-stretch shrink-0 [--kt-drawer-enable:true] lg:[--kt-drawer-enable:false]"
    data-kt-drawer="true" data-kt-drawer-class="kt-drawer kt-drawer-start top-0 bottom-0" id="sidebar">

    <div class="kt-sidebar-header hidden lg:flex items-center relative justify-between px-3 lg:px-6 shrink-0"
        id="sidebar_header">
        <a class="dark:hidden" href="{{ route('admin.dashboard') }}">
            <img class="default-logo min-h-[22px] max-w-none"
                src="{{ !empty(optional($setting)->site_logo_black) && file_exists(public_path('storage/' . optional($setting)->site_logo_black)) ? asset('storage/' . optional($setting)->site_logo_black) : asset('images/logo.webp') }}" />
            <img class="small-logo min-h-[22px] max-w-none"
                src="{{ !empty(optional($setting)->site_favicon) && file_exists(public_path('storage/' . optional($setting)->site_favicon)) ? asset('storage/' . optional($setting)->site_favicon) : asset('images/favicon.jpg') }}" />
        </a>
        <a class="hidden dark:block" href="{{ route('admin.dashboard') }}">
            <img class="default-logo min-h-[22px] max-w-none"
                src="{{ !empty(optional($setting)->site_logo_white) && file_exists(public_path('storage/' . optional($setting)->site_logo_white)) ? asset('storage/' . optional($setting)->site_logo_white) : asset('images/logo.webp') }}" />
            <img class="small-logo min-h-[22px] max-w-none"
                src="{{ !empty(optional($setting)->site_favicon) && file_exists(public_path('storage/' . optional($setting)->site_favicon)) ? asset('storage/' . optional($setting)->site_favicon) : asset('images/favicon.jpg') }}" />
        </a>
        <button
            class="kt-btn kt-btn-outline kt-btn-icon size-[30px] absolute start-full top-2/4 -translate-x-2/4 -translate-y-2/4 rtl:translate-x-2/4"
            data-kt-toggle="body" data-kt-toggle-class="kt-sidebar-collapse" id="sidebar_toggle">
            <i
                class="ki-filled ki-black-left-line kt-toggle-active:rotate-180 transition-all duration-300 rtl:translate rtl:rotate-180 rtl:kt-toggle-active:rotate-0">
            </i>
        </button>
    </div>
    <div class="kt-sidebar-content flex grow shrink-0 py-5 pe-2" id="sidebar_content">
        <div class="kt-scrollable-y-hover grow shrink-0 flex ps-2 lg:ps-5 pe-1 lg:pe-3" data-kt-scrollable="true"
            data-kt-scrollable-dependencies="#sidebar_header" data-kt-scrollable-height="auto"
            data-kt-scrollable-offset="0px" data-kt-scrollable-wrappers="#sidebar_content" id="sidebar_scrollable">

            <div class="kt-menu flex flex-col grow gap-1" data-kt-menu="true" data-kt-menu-accordion-expand-all="false"
                id="sidebar_menu">

                <div class="kt-menu-item {{ Route::is('admin.dashboard') ? 'active' : '' }}">
                    <a class="kt-menu-link border border-transparent items-center grow kt-menu-item-active:bg-accent/60 dark:menu-item-active:border-border kt-menu-item-active:rounded-lg hover:bg-accent/60 hover:rounded-lg gap-[14px] ps-[10px] pe-[10px] py-[8px]"
                        href="{{ route('admin.dashboard') }}" tabindex="0">
                        <span class="kt-menu-icon items-start text-muted-foreground w-[20px]">
                            <i class="ki-filled ki-home text-lg"></i>
                        </span>
                        <span
                            class="kt-menu-title text-sm font-medium text-foreground kt-menu-item-active:text-primary kt-menu-link-hover:!text-primary">
                            Dashboard
                        </span>
                    </a>
                </div>

                @foreach ($menuItems as $item)
                    {{-- ============================================= --}}
                    {{-- Type 1: Menu Heading --}}
                    {{-- ============================================= --}}
                    @if (!isset($item['route']) && empty($item['subMenu']))
                        <div class="kt-menu-item pt-2.25 pb-px">
                            <span
                                class="kt-menu-heading uppercase text-xs font-medium text-muted-foreground ps-[10px] pe-[10px]">
                                {{ $item['title'] }}
                            </span>
                        </div>

                        {{-- ============================================= --}}
                        {{-- Type 2: 1st Level Accordion (Has Submenu) --}}
                        {{-- ============================================= --}}
                    @elseif (!empty($item['subMenu']))
                        <div class="kt-menu-item {{ Route::is(...$item['routes']) ? 'here show' : '' }}"
                            data-kt-menu-item-toggle="accordion" data-kt-menu-item-trigger="click">
                            <div
                                class="kt-menu-link flex items-center grow cursor-pointer border border-transparent gap-[10px] ps-[10px] pe-[10px] py-[6px]">
                                <span class="kt-menu-icon items-start text-muted-foreground w-[20px]">
                                    <i class="{{ $item['icon'] ?? 'ki-filled ki-folder' }} text-lg"></i>
                                </span>
                                <span
                                    class="kt-menu-title text-sm font-medium text-foreground kt-menu-item-active:text-primary kt-menu-link-hover:!text-primary">
                                    {{ $item['title'] }}
                                </span>
                                <span
                                    class="kt-menu-arrow text-muted-foreground w-[20px] shrink-0 justify-end ms-1 me-[-10px]">
                                    <span class="inline-flex kt-menu-item-show:hidden">
                                        <i class="ki-filled ki-plus text-[11px]"></i>
                                    </span>
                                    <span class="hidden kt-menu-item-show:inline-flex">
                                        <i class="ki-filled ki-minus text-[11px]"></i>
                                    </span>
                                </span>
                            </div>

                            <div
                                class="kt-menu-accordion gap-1 ps-[10px] relative before:absolute before:start-[20px] before:top-0 before:bottom-0 before:border-s before:border-border">
                                @foreach ($item['subMenu'] as $subItem)
                                    @if (isset($subItem['subMenu']))
                                        {{-- ============================================= --}}
                                        {{-- 2nd Level Accordion (Has 3rd Level) --}}
                                        {{-- ============================================= --}}
                                        @php
                                            $subSubRoutes = array_reduce(
                                                $subItem['subMenu'],
                                                fn($c, $i) => array_merge($c, $i['routes']),
                                                [],
                                            );
                                        @endphp
                                        <div class="kt-menu-item {{ Route::is(...$subSubRoutes) ? 'here show' : '' }}"
                                            data-kt-menu-item-toggle="accordion" data-kt-menu-item-trigger="click">
                                            <div
                                                class="kt-menu-link border border-transparent grow cursor-pointer gap-[14px] ps-[10px] pe-[10px] py-[8px]">
                                                <span
                                                    class="kt-menu-bullet flex w-[6px] -start-[3px] rtl:start-0 relative before:absolute before:top-0 before:size-[6px] before:rounded-full rtl:before:translate-x-1/2 before:-translate-y-1/2 kt-menu-item-active:before:bg-primary kt-menu-item-hover:before:bg-primary"></span>
                                                <span
                                                    class="kt-menu-title text-2sm font-normal me-1 text-foreground kt-menu-item-active:text-primary kt-menu-item-active:font-medium kt-menu-link-hover:!text-primary">
                                                    {{ $subItem['title'] }}
                                                </span>
                                                <span
                                                    class="kt-menu-arrow text-muted-foreground w-[20px] shrink-0 justify-end ms-1 me-[-10px]">
                                                    <span class="inline-flex kt-menu-item-show:hidden"><i
                                                            class="ki-filled ki-plus text-[11px]"></i></span>
                                                    <span class="hidden kt-menu-item-show:inline-flex"><i
                                                            class="ki-filled ki-minus text-[11px]"></i></span>
                                                </span>
                                            </div>
                                            <div
                                                class="kt-menu-accordion gap-1 relative before:absolute before:start-[32px] ps-[22px] before:top-0 before:bottom-0 before:border-s before:border-border">
                                                @foreach ($subItem['subMenu'] as $subSubItem)
                                                    {{-- ============================================= --}}
                                                    {{-- 3rd Level Link --}}
                                                    {{-- ============================================= --}}
                                                    <div
                                                        class="kt-menu-item {{ Route::is(...$subSubItem['routes']) ? 'active' : '' }}">
                                                        <a class="kt-menu-link border border-transparent items-center grow kt-menu-item-active:bg-accent/60 dark:menu-item-active:border-border kt-menu-item-active:rounded-lg hover:bg-accent/60 hover:rounded-lg gap-[5px] ps-[10px] pe-[10px] py-[8px]"
                                                            href="{{ route($subSubItem['route']) }}">
                                                            <span
                                                                class="kt-menu-bullet flex w-[6px] -start-[3px] rtl:start-0 relative before:absolute before:top-0 before:size-[6px] before:rounded-full rtl:before:translate-x-1/2 before:-translate-y-1/2 kt-menu-item-active:before:bg-primary kt-menu-item-hover:before:bg-primary"></span>
                                                            <span
                                                                class="kt-menu-title text-2sm font-normal text-foreground kt-menu-item-active:text-primary kt-menu-item-active:font-semibold kt-menu-link-hover:!text-primary">
                                                                {{ $subSubItem['title'] }}
                                                            </span>
                                                        </a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @else
                                        {{-- ============================================= --}}
                                        {{-- 2nd Level Link (No 3rd Level) --}}
                                        {{-- ============================================= --}}
                                        <div
                                            class="kt-menu-item {{ Route::is(...$subItem['routes']) ? 'active' : '' }}">
                                            <a class="kt-menu-link border border-transparent items-center grow kt-menu-item-active:bg-accent/60 dark:menu-item-active:border-border kt-menu-item-active:rounded-lg hover:bg-accent/60 hover:rounded-lg gap-[1s4px] ps-[10px] pe-[10px] py-[8px]"
                                                href="{{ route($subItem['route']) }}">
                                                <span
                                                    class="kt-menu-bullet flex w-[6px] -start-[3px] rtl:start-0 relative before:absolute before:top-0 before:size-[6px] before:rounded-full rtl:before:translate-x-1/2 before:-translate-y-1/2 kt-menu-item-active:before:bg-primary kt-menu-item-hover:before:bg-primary"></span>
                                                <span
                                                    class="kt-menu-title text-2sm font-normal text-foreground kt-menu-item-active:text-primary kt-menu-item-active:font-semibold kt-menu-link-hover:!text-primary">
                                                    {{ $subItem['title'] }}
                                                </span>
                                            </a>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        {{-- ============================================= --}}
                        {{-- Type 3: 1st Level Link (No Submenu) --}}
                        {{-- ============================================= --}}
                    @else
                        <div class="kt-menu-item {{ Route::is(...$item['routes']) ? 'active' : '' }}">
                            <a class="kt-menu-link border border-transparent items-center grow kt-menu-item-active:bg-accent/60 dark:menu-item-active:border-border kt-menu-item-active:rounded-lg hover:bg-accent/60 hover:rounded-lg gap-[10px] ps-[10px] pe-[10px] py-[8px]"
                                href="{{ route($item['route']) }}">
                                <span class="kt-menu-icon items-start text-muted-foreground w-[20px]">
                                    <i class="{{ $item['icon'] ?? 'ki-filled ki-abstract-28' }} text-lg"></i>
                                </span>
                                <span
                                    class="kt-menu-title text-sm font-medium text-foreground kt-menu-item-active:text-primary kt-menu-link-hover:!text-primary">
                                    {{ $item['title'] }}
                                </span>
                            </a>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

</div>
