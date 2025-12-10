<x-admin-app-layout :title="'Academic Pages'">

    <div class="card card-flash">
        <div class="card-header align-items-center">
            <h3 class="card-title fw-bold">Academic Pages</h3>

            <div class="card-toolbar d-flex">
                <form method="GET" action="{{ route('admin.academic.pages.index') }}" class="me-3">
                    <div class="d-flex align-items-center">
                        <select name="site_id" class="form-select form-select-sm" onchange="this.form.submit()">
                            @foreach ($sites as $site)
                                <option value="{{ $site->id }}" {{ optional($selectedSite)->id == $site->id ? 'selected' : '' }}>
                                    {{ $site->name }} ({{ $site->short_name }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>

                @can('create academic pages')
                    @if ($selectedSite)
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createPageModal">
                            <i class="fa fa-plus me-2"></i> Add Page
                        </button>
                    @endif
                @endcan
            </div>
        </div>

        <div class="card-body">
            @if (!$selectedSite)
                <p class="text-muted">Please create an academic site first.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-row-bordered table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Key</th>
                                <th>Slug</th>
                                <th>Home?</th>
                                <th>Active?</th>
                                <th>Position</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pages as $page)
                                <tr>
                                    <td>{{ $page->title }}</td>
                                    <td><span class="badge badge-light">{{ $page->page_key }}</span></td>
                                    <td><code>{{ $page->slug }}</code></td>
                                    <td>{!! $page->is_home ? '<span class="badge bg-success">Yes</span>' : '' !!}</td>
                                    <td>{!! $page->is_active ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>' !!}</td>
                                    <td>{{ $page->position }}</td>

                                    <td class="text-end">
                                        @can('edit academic pages')
                                            <button class="btn btn-light-success btn-sm editPageBtn"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editPageModal"
                                                data-json='@json($page)'
                                                data-banner="{{ $page->banner_image }}"
                                                data-og="{{ $page->og_image }}">
                                                <i class="fa-solid fa-pen fs-6"></i>
                                            </button>
                                        @endcan

                                        @can('delete academic pages')
                                            <a href="{{ route('admin.academic.pages.destroy', $page->id) }}" class="delete ms-2">
                                                <i class="fa-solid fa-trash text-danger fs-4"></i>
                                            </a>
                                        @endcan
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>


    {{-- ---------------------------------------------------------
         CREATE PAGE MODAL  (unchanged)
    ---------------------------------------------------------- --}}
    @if ($selectedSite)
        @include('admin.pages.academic.partials.page_create', ['selectedSite' => $selectedSite])
    @endif



    {{-- ---------------------------------------------------------
         FULL EDIT PAGE MODAL  (NEW COMPLETE FORM)
    ---------------------------------------------------------- --}}
    <div class="modal fade" id="editPageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <form id="editPageForm" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title">Edit Page</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <input type="hidden" name="_method" value="POST">

                        {{-- BASIC --}}
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="col-form-label fw-bold fs-6">Page Key</label>
                                <input type="text" name="page_key" id="edit_page_key" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-4">
                                <label class="col-form-label fw-bold fs-6">Slug</label>
                                <input type="text" name="slug" id="edit_slug" class="form-control form-control-sm" required>
                            </div>
                            <div class="col-md-4">
                                <label class="col-form-label fw-bold fs-6">Title</label>
                                <input type="text" name="title" id="edit_title" class="form-control form-control-sm" required>
                            </div>
                        </div>

                        {{-- BASIC 2 --}}
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="col-form-label fw-bold fs-6">Subtitle</label>
                                <input type="text" name="subtitle" id="edit_subtitle" class="form-control form-control-sm">
                            </div>

                            <div class="col-md-4 d-flex align-items-center">
                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="checkbox" name="is_home" value="1" id="edit_is_home">
                                    <label class="form-check-label">Is Home Page?</label>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="col-form-label fw-bold fs-6">Active</label>
                                <select name="is_active" id="edit_is_active" class="form-select form-select-sm">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>

                        {{-- HOME EXTRAS --}}
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="col-form-label fw-bold fs-6">Banner Title</label>
                                <input type="text" name="banner_title" id="edit_banner_title" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-3">
                                <label class="col-form-label fw-bold fs-6">Banner Subtitle</label>
                                <input type="text" name="banner_subtitle" id="edit_banner_subtitle" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-3">
                                <label class="col-form-label fw-bold fs-6">Banner Button Text</label>
                                <input type="text" name="banner_button" id="edit_banner_button" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-3">
                                <label class="col-form-label fw-bold fs-6">Banner Button URL</label>
                                <input type="text" name="banner_button_url" id="edit_banner_button_url" class="form-control form-control-sm">
                            </div>
                        </div>

                        {{-- IMAGES --}}
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="col-form-label fw-bold fs-6">Banner Image</label>
                                <x-metronic.image-input name="banner_image" id="edit_banner_image" :source="''" />
                            </div>
                            <div class="col-md-6">
                                <label class="col-form-label fw-bold fs-6">OG Image</label>
                                <x-metronic.image-input name="og_image" id="edit_og_image" :source="''" />
                            </div>
                        </div>

                        {{-- CONTENT --}}
                        <x-metronic.editor name="content" label="Page Content" :value="''" id="edit_content" rows="12" />

                        {{-- SEO --}}
                        <div class="row mt-4">
                            <div class="col-md-4">
                                <label class="col-form-label fw-bold fs-6">Meta Title</label>
                                <input type="text" name="meta_title" id="edit_meta_title" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-4">
                                <label class="col-form-label fw-bold fs-6">Meta Tags</label>
                                <input type="text" name="meta_tags" id="edit_meta_tags" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-4">
                                <label class="col-form-label fw-bold fs-6">Meta Description</label>
                                <textarea name="meta_description" id="edit_meta_description" class="form-control form-control-sm" rows="3"></textarea>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm">Save Changes</button>
                    </div>

                </form>
            </div>
        </div>
    </div>



    @push('scripts')
        <script>
            $(document).on('click', '.editPageBtn', function () {
                const page = $(this).data('json');
                const banner = $(this).data('banner');
                const og = $(this).data('og');

                const form = $('#editPageForm');
                form.attr('action', "{{ url('admin/academic/pages') }}/" + page.id);

                $('#edit_page_key').val(page.page_key ?? '');
                $('#edit_slug').val(page.slug);
                $('#edit_title').val(page.title);
                $('#edit_subtitle').val(page.subtitle ?? '');

                $('#edit_is_home').prop('checked', page.is_home);
                $('#edit_is_active').val(page.is_active ? '1' : '0');

                $('#edit_banner_title').val(page.banner_title ?? '');
                $('#edit_banner_subtitle').val(page.banner_subtitle ?? '');
                $('#edit_banner_button').val(page.banner_button ?? '');
                $('#edit_banner_button_url').val(page.banner_button_url ?? '');

                $('#edit_content').val(page.content ?? '');

                $('#edit_meta_title').val(page.meta_title ?? '');
                $('#edit_meta_tags').val(page.meta_tags ?? '');
                $('#edit_meta_description').val(page.meta_description ?? '');

                // image preview init
                if (banner) {
                    $('#edit_banner_image').attr('data-kt-image-input-src', "{{ asset('storage') }}/" + banner);
                }
                if (og) {
                    $('#edit_og_image').attr('data-kt-image-input-src', "{{ asset('storage') }}/" + og);
                }
            });
        </script>
    @endpush


</x-admin-app-layout>
