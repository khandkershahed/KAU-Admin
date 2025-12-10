<x-admin-app-layout :title="'Academic Sites & Menus'">

    <div class="card">
        <div class="card-header align-items-center">
            <h3 class="card-title fw-bold">Academic Sites & Menus</h3>

            <div class="card-toolbar d-flex">
                @can('create academic groups')
                    <button class="btn btn-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#createGroupModal">
                        <i class="fa fa-plus me-2"></i> Add Group
                    </button>
                @endcan

                @can('create academic sites')
                    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#createSiteModal">
                        <i class="fa fa-plus me-2"></i> Add Site
                    </button>
                @endcan
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                {{-- LEFT: Groups & Sites --}}
                <div class="col-md-6">
                    <div class="accordion" id="academicGroupsAccordion">
                        @foreach ($groups as $group)
                            <div class="accordion-item mb-4 group-item" data-id="{{ $group->id }}">
                                <div class="accordion-header d-flex align-items-center justify-content-between px-3 py-2"
                                    style="background: #f8fafc;">

                                    <div class="d-flex align-items-center flex-grow-1 group-sort" style="cursor: grab;">
                                        <span class="me-3">
                                            <i class="fa-solid fa-up-down text-muted"></i>
                                        </span>
                                        <button
                                            class="accordion-button collapsed py-1 px-2 shadow-none bg-transparent flex-grow-1"
                                            type="button" data-bs-toggle="collapse"
                                            data-bs-target="#group-{{ $group->id }}">
                                            <span class="fw-semibold">
                                                {{ $group->title }}
                                                <small class="text-muted">({{ $group->slug }})</small>
                                            </span>
                                        </button>
                                    </div>

                                    <div class="d-flex align-items-center ms-3">
                                        @can('edit academic groups')
                                            <button class="btn btn-light-success btn-sm me-2 editGroupBtn"
                                                data-id="{{ $group->id }}" data-title="{{ $group->title }}"
                                                data-slug="{{ $group->slug }}" data-position="{{ $group->position }}"
                                                data-active="{{ $group->is_active ? 1 : 0 }}">
                                                <i class="fa-solid fa-pen-to-square fs-6"></i>
                                            </button>
                                        @endcan

                                        @can('delete academic groups')
                                            <a href="{{ route('admin.academic.groups.destroy', $group->id) }}"
                                                class="delete">
                                                <i class="fa-solid fa-trash text-danger fs-4"></i>
                                            </a>
                                        @endcan
                                    </div>
                                </div>

                                <div id="group-{{ $group->id }}"
                                    class="accordion-collapse collapse @if ($loop->first) show @endif">
                                    <div class="accordion-body">

                                        <div class="mb-3 text-end">
                                            @can('create academic sites')
                                                <button class="btn btn-sm btn-primary createSiteBtn"
                                                    data-group-id="{{ $group->id }}">
                                                    <i class="fa fa-plus me-2"></i> Add Site in {{ $group->title }}
                                                </button>
                                            @endcan
                                        </div>

                                        <ul class="list-group site-sortable" data-group-id="{{ $group->id }}">
                                            @foreach ($group->sites as $site)
                                                <li class="list-group-item d-flex align-items-center justify-content-between mb-2 site-item"
                                                    data-id="{{ $site->id }}" style="cursor: grab;">

                                                    <div class="d-flex align-items-center">
                                                        <span class="me-3">
                                                            <i class="fa-solid fa-up-down text-muted"></i>
                                                        </span>
                                                        <a href="{{ route('admin.academic.sites.index', ['site_id' => $site->id]) }}"
                                                            class="fw-semibold">
                                                            {{ $site->name }}
                                                            <small class="text-muted">({{ $site->short_name }})</small>
                                                        </a>
                                                    </div>

                                                    <div class="d-flex align-items-center">
                                                        @can('edit academic sites')
                                                            <button class="btn btn-light-success btn-sm me-2 editSiteBtn"
                                                                data-id="{{ $site->id }}"
                                                                data-group-id="{{ $group->id }}"
                                                                data-name="{{ $site->name }}"
                                                                data-short_name="{{ $site->short_name }}"
                                                                data-slug="{{ $site->slug }}"
                                                                data-base_url="{{ $site->base_url }}"
                                                                data-theme_primary_color="{{ $site->theme_primary_color }}"
                                                                data-theme_secondary_color="{{ $site->theme_secondary_color }}"
                                                                data-status="{{ $site->status }}">
                                                                <i class="fa-solid fa-pen fs-6"></i>
                                                            </button>
                                                        @endcan

                                                        @can('delete academic sites')
                                                            <a href="{{ route('admin.academic.sites.destroy', $site->id) }}"
                                                                class="delete">
                                                                <i class="fa-solid fa-trash text-danger fs-4"></i>
                                                            </a>
                                                        @endcan
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- RIGHT: Navigation for selected site --}}
                <div class="col-md-6">
                    @if ($selectedSite)
                        <h5 class="fw-bold mb-3">
                            Menus for: {{ $selectedSite->name }}
                        </h5>

                        @can('edit academic nav')
                            <button class="btn btn-sm btn-primary mb-3" data-bs-toggle="modal"
                                data-bs-target="#createNavItemModal">
                                <i class="fa fa-plus me-2"></i> Add Menu Item
                            </button>
                        @endcan

                        <div class="border rounded p-3">
                            <ul class="list-group nav-sortable" id="navSortable"
                                data-site-id="{{ $selectedSite->id }}">
                                @foreach ($navItemsTree as $node)
                                    @include('admin.pages.academic.partials.nav_item', [
                                        'node' => $node,
                                        'level' => 0,
                                    ])
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <p class="text-muted">Select a site to manage its menu.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Create Group Modal --}}
    @include('admin.pages.academic.modals.group_modals')

    {{-- Create / Edit Site Modal --}}
    @include('admin.pages.academic.modals.site_modals', ['groups' => $groups])

    {{-- Create Nav Item Modal --}}
    @if ($selectedSite)
        @include('admin.pages.academic.modals.nav_modals', ['site' => $selectedSite])
    @endif

    @push('scripts')
        <script>
            
            const groupSortUrl = "{{ route('admin.academic.groups.sort') }}";
            const siteSortUrl = "{{ route('admin.academic.sites.sort') }}";
            const navSortUrl = "{{ $selectedSite ? route('admin.academic.nav.sort', $selectedSite->id) : '' }}";
            const csrfToken = "{{ csrf_token() }}";

            // GROUP SORT
            $('.accordion').on('mouseenter', function() {
                $('.group-item').closest('#academicGroupsAccordion')
                    .sortable({
                        handle: '.group-sort',
                        update: function() {
                            const order = [];
                            $('#academicGroupsAccordion .group-item').each(function() {
                                order.push($(this).data('id'));
                            });

                            $.post(groupSortUrl, {
                                order,
                                _token: csrfToken
                            }, function(res) {
                                Swal.fire('Updated', res.message, 'success');
                            });
                        }
                    });
            });

            // SITE SORT
            $('.site-sortable').each(function() {
                const $list = $(this);
                $list.sortable({
                    handle: '.site-item',
                    update: function() {
                        const order = [];
                        $list.find('.site-item').each(function() {
                            order.push($(this).data('id'));
                        });

                        $.post(siteSortUrl, {
                            order,
                            _token: csrfToken
                        }, function(res) {
                            Swal.fire('Updated', res.message, 'success');
                        });
                    }
                });
            });

            // NAV SORT (flat)
            @if ($selectedSite)
                $('#navSortable').sortable({
                    handle: '.nav-handle',
                    update: function() {
                        const order = [];
                        $('#navSortable').find('.nav-item-row').each(function() {
                            order.push($(this).data('id'));
                        });

                        $.post(navSortUrl, {
                            order,
                            _token: csrfToken
                        }, function(res) {
                            Swal.fire('Updated', res.message, 'success');
                        });
                    }
                });
            @endif

            // Global delete handler using your pattern
            $(document).on('click', 'a.delete', function(e) {
                e.preventDefault();
                const url = $(this).attr('href');

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This action cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (!result.isConfirmed) return;

                    fetch(url, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            }
                        })
                        .then(res => res.json())
                        .then(json => {
                            if (json.success) {
                                Swal.fire('Deleted!', 'Item removed successfully.', 'success').then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire('Error', json.message || 'Delete failed', 'error');
                            }
                        })
                        .catch(() => Swal.fire('Error', 'Delete failed', 'error'));
                });
            });
        </script>
    @endpush
</x-admin-app-layout>
