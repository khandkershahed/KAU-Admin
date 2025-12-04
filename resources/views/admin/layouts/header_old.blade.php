<header class="kt-header fixed top-0 z-10 start-0 end-0 flex items-stretch shrink-0 bg-background" data-kt-sticky="true"
    data-kt-sticky-class="border-b border-border" data-kt-sticky-name="header" id="header">

    <div class="kt-container-fixed flex justify-between items-stretch lg:gap-4" id="headerContainer">

        <div class="flex gap-2.5 lg:hidden items-center -ms-1">
            <a class="shrink-0" href="html/demo1.html">
                <img class="max-h-[25px] w-full"
                    src="{{ get_image(optional($setting)->site_logo_black, 'images/logo.webp') }}" />
            </a>
            <div class="flex items-center">
                <button class="kt-btn kt-btn-icon kt-btn-ghost" data-kt-drawer-toggle="#sidebar">
                    <i class="ki-filled ki-menu">
                    </i>
                </button>
            </div>
        </div>


        <div class="flex [.kt-header_&]:below-lg:hidden items-center gap-1.25 text-xs lg:text-sm font-medium mb-2.5 lg:mb-0 [--kt-reparent-target:#contentContainer] lg:[--kt-reparent-target:#headerContainer] [--kt-reparent-mode:prepend] lg:[--kt-reparent-mode:prepend]"
            data-kt-reparent="true">
            <span class="text-secondary-foreground">
                My Account
            </span>
            <i class="ki-filled ki-right text-muted-foreground text-[10px]">
            </i>
            <span class="text-secondary-foreground">
                Security
            </span>
            <i class="ki-filled ki-right text-muted-foreground text-[10px]">
            </i>
            <span class="text-mono font-medium">
                Get Started
            </span>
        </div>


        <div class="flex items-center gap-2.5">
            @php
                $profileData = Auth::guard('admin')->user();
                $avatar =
                    'https://ui-avatars.com/api/?name=' .
                    urlencode($profileData->name) .
                    '&background=0D8ABC&color=fff&size=128&rounded=true';
                $roles = Spatie\Permission\Models\Role::latest()->get();

                $routes = Route::current()->getName();
            @endphp



            {{-- <button
                class="kt-btn kt-btn-ghost kt-btn-icon size-9 rounded-full hover:bg-primary/10 hover:[&_i]:text-primary"
                data-kt-drawer-toggle="#notifications_drawer">
                <i class="ki-filled ki-notification-status text-lg">
                </i>
            </button>

            <div class="hidden kt-drawer kt-drawer-end card flex-col max-w-[90%] w-[450px] top-5 bottom-5 end-5 rounded-xl border border-border"
                data-kt-drawer="true" data-kt-drawer-container="body" id="notifications_drawer">
                <div class="flex items-center justify-between gap-2.5 text-sm text-mono font-semibold px-5 py-2.5 border-b border-b-border"
                    id="notifications_header">
                    Notifications
                    <button class="kt-btn kt-btn-sm kt-btn-icon kt-btn-dim shrink-0" data-kt-drawer-dismiss="true">
                        <i class="ki-filled ki-cross">
                        </i>
                    </button>
                </div>
                <div class="kt-tabs kt-tabs-line justify-between px-5 mb-2" data-kt-tabs="true" id="notifications_tabs">
                    <div class="flex items-center gap-5">
                        <button class="kt-tab-toggle py-3 active" data-kt-tab-toggle="#notifications_tab_all">
                            All
                        </button>
                        <button class="kt-tab-toggle py-3 relative" data-kt-tab-toggle="#notifications_tab_inbox">
                            Inbox
                            <span
                                class="rounded-full bg-green-500 size-[5px] absolute top-2 rtl:start-0 end-0 transform translate-y-1/2 translate-x-full">
                            </span>
                        </button>
                    </div>

                </div>
                <div class="grow flex flex-col" id="notifications_tab_all">
                    <div class="grow kt-scrollable-y-auto" data-kt-scrollable="true"
                        data-kt-scrollable-dependencies="#header" data-kt-scrollable-max-height="auto"
                        data-kt-scrollable-offset="150px">
                        <div class="grow flex flex-col gap-5 pt-3 pb-4 divider-y divider-border">

                            <div class="flex grow gap-2.5 px-5">
                                <div class="kt-avatar size-8">
                                    <div class="kt-avatar-image">
                                        <img alt="avatar" src="assets/media/avatars/300-5.png" />
                                    </div>
                                    <div class="kt-avatar-indicator -end-2 -bottom-2">
                                        <div class="kt-avatar-status kt-avatar-status-online size-2.5">
                                        </div>
                                    </div>
                                </div>
                                <div class="flex flex-col gap-3.5">
                                    <div class="flex flex-col gap-1">
                                        <div class="text-sm font-medium mb-px">
                                            <a class="hover:text-primary text-mono font-semibold" href="#">
                                                Leslie Alexander
                                            </a>
                                            <span class="text-secondary-foreground">
                                                added new tags to
                                            </span>
                                            <a class="hover:text-primary text-primary" href="#">
                                                Web Redesign 2024
                                            </a>
                                        </div>
                                        <span class="flex items-center text-xs font-medium text-muted-foreground">
                                            53 mins ago
                                            <span class="rounded-full size-1 bg-mono/30 mx-1.5">
                                            </span>
                                            ACME
                                        </span>
                                    </div>
                                    <div class="flex flex-wrap gap-2.5">
                                        <span class="kt-badge kt-badge-sm kt-badge-info kt-badge-outline">
                                            Client-Request
                                        </span>
                                        <span class="kt-badge kt-badge-sm kt-badge-warning kt-badge-outline">
                                            Figma
                                        </span>
                                        <span class="kt-badge kt-badge-sm kt-badge-secondary kt-badge-outline">
                                            Redesign
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="border-b border-b-border">
                            </div>
                            <div class="flex grow gap-2.5 px-5" id="notification_request_3">
                                <div class="kt-avatar size-8">
                                    <div class="kt-avatar-image">
                                        <img alt="avatar" src="assets/media/avatars/300-27.png" />
                                    </div>
                                    <div class="kt-avatar-indicator -end-2 -bottom-2">
                                        <div class="kt-avatar-status kt-avatar-status-online size-2.5">
                                        </div>
                                    </div>
                                </div>
                                <div class="flex flex-col gap-3.5">
                                    <div class="flex flex-col gap-1">
                                        <div class="text-sm font-medium mb-px">
                                            <a class="hover:text-primary text-mono font-semibold" href="#">
                                                Guy Hawkins
                                            </a>
                                            <span class="text-secondary-foreground">
                                                requested access to
                                            </span>
                                            <a class="hover:text-primary text-primary" href="#">
                                                AirSpace
                                            </a>
                                            <span class="text-secondary-foreground">
                                                project
                                            </span>
                                        </div>
                                        <span class="flex items-center text-xs font-medium text-muted-foreground">
                                            14 hours ago
                                            <span class="rounded-full size-1 bg-mono/30 mx-1.5">
                                            </span>
                                            Dev Team
                                        </span>
                                    </div>
                                    <div class="flex flex-wrap gap-2.5">
                                        <button class="kt-btn kt-btn-outline kt-btn-sm"
                                            data-kt-dismiss="#notification_request_3">
                                            Decline
                                        </button>
                                        <button class="kt-btn kt-btn-mono kt-btn-sm"
                                            data-kt-dismiss="#notification_request_3">
                                            Accept
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="border-b border-b-border">
                            </div>
                            <div class="flex grow gap-2.5 px-5">
                                <div class="kt-avatar size-8">
                                    <div class="kt-avatar-image">
                                        <img alt="avatar" src="assets/media/avatars/300-11.png">
                                        </img>
                                    </div>
                                    <div class="kt-avatar-indicator -end-2 -bottom-2">
                                        <div class="kt-avatar-status kt-avatar-status-online size-2.5">
                                        </div>
                                    </div>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <div class="text-sm font-medium mb-px">
                                        <a class="hover:text-primary text-mono font-semibold" href="#">
                                            Raymond Pawell
                                        </a>
                                        <span class="text-secondary-foreground">
                                            posted a new article
                                        </span>
                                        <a class="hover:text-primary text-primary" href="#">
                                            2024 Roadmap
                                        </a>
                                    </div>
                                    <span class="flex items-center text-xs font-medium text-muted-foreground">
                                        1 hour ago
                                        <span class="rounded-full size-1 bg-mono/30 mx-1.5">
                                        </span>
                                        Roadmap
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="border-b border-b-border">
                    </div>
                    <div class="grid grid-cols-2 p-5 gap-2.5" id="notifications_all_footer">
                        <button class="kt-btn kt-btn-outline justify-center">
                            Archive all
                        </button>
                        <button class="kt-btn kt-btn-outline justify-center">
                            Mark all as read
                        </button>
                    </div>
                </div>
                <div class="grow flex flex-col hidden" id="notifications_tab_inbox">
                    <div class="grow kt-scrollable-y-auto" data-kt-scrollable="true"
                        data-kt-scrollable-dependencies="#header" data-kt-scrollable-max-height="auto"
                        data-kt-scrollable-offset="150px">

                    </div>

                </div>
            </div> --}}


            <div class="shrink-0" data-kt-dropdown="true" data-kt-dropdown-offset="10px, 10px"
                data-kt-dropdown-offset-rtl="-20px, 10px" data-kt-dropdown-placement="bottom-end"
                data-kt-dropdown-placement-rtl="bottom-start" data-kt-dropdown-trigger="click">
                <div class="cursor-pointer shrink-0" data-kt-dropdown-toggle="true">
                    <img alt="" class="size-9 rounded-full border-2 border-green-500 shrink-0"
                        src="{{ !empty($profileData->photo) ? asset('storage/' . $profileData->photo) : $avatar }}" />
                </div>
                <div class="kt-dropdown-menu w-[250px]" data-kt-dropdown-menu="true">
                    <div class="flex items-center justify-between px-2.5 py-1.5 gap-1.5">
                        <div class="flex items-center gap-2">
                            <img alt="" class="size-9 shrink-0 rounded-full border-2 border-green-500"
                                src="{{ !empty($profileData->photo) ? asset('storage/' . $profileData->photo) : $avatar }}" />
                            <div class="flex flex-col gap-1.5" style="overflow-wrap: anywhere;">
                                <span class="text-sm text-foreground font-semibold leading-none">
                                    {{ $profileData->name }}
                                </span>
                                <a class="text-xs text-secondary-foreground hover:text-primary font-medium leading-none"
                                    href="mailto:{{ $profileData->email }}">
                                    {{ $profileData->email }}
                                </a>
                            </div>
                        </div>
                        {{-- <span class="kt-badge kt-badge-sm kt-badge-primary kt-badge-outline">
                            Pro
                        </span> --}}
                    </div>
                    <ul class="kt-dropdown-menu-sub">
                        <li>
                            <div class="kt-dropdown-menu-separator">
                            </div>
                        </li>

                        <li>
                            <a class="kt-dropdown-menu-link" href="{{ route('admin.profile.edit') }}">
                                <i class="ki-filled ki-profile-circle">
                                </i>
                                My Profile
                            </a>
                        </li>

                        <li>
                            <div class="kt-dropdown-menu-separator">
                            </div>
                        </li>
                    </ul>
                    <div class="px-2.5 pt-1.5 mb-2.5 flex flex-col gap-3.5">
                        <div class="flex items-center gap-2 justify-between">
                            <span class="flex items-center gap-2">
                                <i class="ki-filled ki-moon text-base text-muted-foreground">
                                </i>
                                <span class="font-medium text-2sm">
                                    Dark Mode
                                </span>
                            </span>
                            <input class="kt-switch" data-kt-theme-switch-state="dark"
                                data-kt-theme-switch-toggle="true" name="check" type="checkbox" value="1" />
                        </div>
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <a class="kt-btn kt-btn-outline justify-center w-full" href="{{ route('admin.logout') }}"
                                onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                Log out
                            </a>
                        </form>
                    </div>
                </div>
            </div>

        </div>

    </div>

</header>
