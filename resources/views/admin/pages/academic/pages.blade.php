<x-admin-app-layout :title="'Academic Pages'">

    <div class="card card-flash">
        <div class="card-header align-items-center">
            <h3 class="card-title fw-bold">Academic Pages</h3>

            <div class="card-toolbar d-flex">
                <form method="GET" action="{{ route('admin.academic.pages.index') }}" class="me-3">
                    <div class="d-flex align-items-center">
                        <select name="site_id" class="form-select form-select-sm" onchange="this.form.submit()">
                            @foreach ($sites as $site)
                                <option value="{{ $site->id }}"
                                    {{ optional($selectedSite)->id == $site->id ? 'selected' : '' }}>
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
                                    <td><span class="badge bg-light">{{ $page->page_key }}</span></td>
                                    <td><code>{{ $page->slug }}</code></td>
                                    <td>{!! $page->is_home ? '<span class="badge bg-success">Yes</span>' : '' !!}</td>
                                    <td>{!! $page->is_active
                                        ? '<span class="badge bg-success">Active</span>'
                                        : '<span class="badge bg-secondary">Inactive</span>' !!}</td>
                                    <td>{{ $page->position }}</td>
                                    <td class="text-end">
                                        @can('edit academic pages')
                                            <button class="btn btn-light-success btn-sm editPageBtn"
                                                data-bs-toggle="modal" data-bs-target="#editPageModal"
                                                data-json='@json($page)'>
                                                <i class="fa-solid fa-pen fs-6"></i>
                                            </button>
                                        @endcan

                                        @can('delete academic pages')
                                            <a href="{{ route('admin.academic.pages.destroy', $page->id) }}"
                                                class="delete ms-2">
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

    {{-- Create Page Modal --}}
    @if ($selectedSite)
        <div class="modal fade" id="createPageModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{ route('admin.academic.pages.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="academic_site_id" value="{{ $selectedSite->id }}">

                        <div class="modal-header">
                            <h5 class="modal-title">Create Page ({{ $selectedSite->short_name }})</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <x-metronic.label class="col-form-label fw-bold fs-6">Page Key</x-metronic.label>
                                    <input type="text" name="page_key" class="form-control form-control-sm"
                                        placeholder="about, facilities, research...">
                                </div>
                                <div class="col-md-4">
                                    <x-metronic.label class="col-form-label fw-bold fs-6">Slug</x-metronic.label>
                                    <input type="text" name="slug" class="form-control form-control-sm"
                                        required>
                                </div>
                                <div class="col-md-4">
                                    <x-metronic.label class="col-form-label fw-bold fs-6">Title</x-metronic.label>
                                    <input type="text" name="title" class="form-control form-control-sm"
                                        required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <x-metronic.label class="col-form-label fw-bold fs-6">Subtitle</x-metronic.label>
                                    <input type="text" name="subtitle" class="form-control form-control-sm">
                                </div>
                                <div class="col-md-4 d-flex align-items-center">
                                    <div class="form-check mt-4">
                                        <input class="form-check-input" type="checkbox" name="is_home"
                                            value="1" id="createIsHome">
                                        <label class="form-check-label" for="createIsHome">Is Home Page?</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <x-metronic.label class="col-form-label fw-bold fs-6">Active</x-metronic.label>
                                    <select name="is_active" class="form-select form-select-sm">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Home Extras --}}
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <x-metronic.label class="col-form-label fw-bold fs-6">Banner
                                        Title</x-metronic.label>
                                    <input type="text" name="banner_title" class="form-control form-control-sm">
                                </div>
                                <div class="col-md-3">
                                    <x-metronic.label class="col-form-label fw-bold fs-6">Banner
                                        Subtitle</x-metronic.label>
                                    <input type="text" name="banner_subtitle"
                                        class="form-control form-control-sm">
                                </div>
                                <div class="col-md-3">
                                    <x-metronic.label class="col-form-label fw-bold fs-6">Banner Button
                                        Text</x-metronic.label>
                                    <input type="text" name="banner_button" class="form-control form-control-sm">
                                </div>
                                <div class="col-md-3">
                                    <x-metronic.label class="col-form-label fw-bold fs-6">Banner Button
                                        URL</x-metronic.label>
                                    <input type="text" name="banner_button_url"
                                        class="form-control form-control-sm">
                                </div>
                            </div>

                            {{-- Images --}}
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <x-metronic.label class="col-form-label fw-bold fs-6">Banner
                                        Image</x-metronic.label>
                                    <x-metronic.image-input name="banner_image" id="bannerImageCreate"
                                        :source="''" />
                                </div>
                                <div class="col-md-6">
                                    <x-metronic.label class="col-form-label fw-bold fs-6">OG Image</x-metronic.label>
                                    <x-metronic.image-input name="og_image" id="ogImageCreate" :source="''" />
                                </div>
                            </div>

                            {{-- Content Editor --}}
                            <x-metronic.editor name="content" label="Page Content" :value="old('content', '')"
                                rows="12" />

                            {{-- SEO --}}
                            <div class="row mt-4">
                                <div class="col-md-4">
                                    <x-metronic.label class="col-form-label fw-bold fs-6">Meta Title</x-metronic.label>
                                    <input type="text" name="meta_title" class="form-control form-control-sm">
                                </div>
                                <div class="col-md-4">
                                    <x-metronic.label class="col-form-label fw-bold fs-6">Meta Tags</x-metronic.label>
                                    <input type="text" name="meta_tags" class="form-control form-control-sm">
                                </div>
                                <div class="col-md-4">
                                    <x-metronic.label class="col-form-label fw-bold fs-6">Meta
                                        Description</x-metronic.label>
                                    <textarea name="meta_description" class="form-control form-control-sm" rows="3"></textarea>
                                </div>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-light btn-sm"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary btn-sm">Save Page</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Edit Page Modal --}}
    <div class="modal fade" id="editPageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content" id="editPageModalContent">
                {{-- Filled via JS with a simple form or use a dedicated include --}}
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const csrfToken = "{{ csrf_token() }}";

            // Populate edit modal
            $(document).on('click', '.editPageBtn', function() {
                const page = $(this).data('json');

                let modalHtml = `
                        <form action="{{ url('admin/academic/pages') }}/${page.id}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Page: ${page.title}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="_method" value="POST">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="col-form-label fw-bold fs-6">Page Key</label>
                                    <input type="text" name="page_key" class="form-control form-control-sm" value="${page.page_key ?? ''}">
                                </div>
                                <div class="col-md-4">
                                    <label class="col-form-label fw-bold fs-6">Slug</label>
                                    <input type="text" name="slug" class="form-control form-control-sm" value="${page.slug}">
                                </div>
                                <div class="col-md-4">
                                    <label class="col-form-label fw-bold fs-6">Title</label>
                                    <input type="text" name="title" class="form-control form-control-sm" value="${page.title}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="col-form-label fw-bold fs-6">Subtitle</label>
                                    <input type="text" name="subtitle" class="form-control form-control-sm" value="${page.subtitle ?? ''}">
                                </div>
                                <div class="col-md-4 d-flex align-items-center">
                                    <div class="form-check mt-4">
                                        <input class="form-check-input" type="checkbox" name="is_home" value="1" ${page.is_home ? 'checked' : ''}>
                                        <label class="form-check-label">Is Home Page?</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="col-form-label fw-bold fs-6">Active</label>
                                    <select name="is_active" class="form-select form-select-sm">
                                        <option value="1" ${page.is_active ? 'selected' : ''}>Active</option>
                                        <option value="0" ${!page.is_active ? 'selected' : ''}>Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mt-3">
                                <p class="text-muted">For banner images and content editing, please open dedicated edit page (if you want we can later make full AJAX edit form here).</p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary btn-sm">Save Changes</button>
                        </div>
                        </form>
                        `;

                $('#editPageModalContent').html(modalHtml);
            });

            // delete buttons same global handler as before (already added in module 1)
        </script>
    @endpush
</x-admin-app-layout>

