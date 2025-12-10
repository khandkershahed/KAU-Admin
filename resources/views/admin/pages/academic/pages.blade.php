<x-admin-app-layout :title="'Academic Pages'">

    <div class="card card-flash">
        <div class="card-header align-items-center">
            <h3 class="card-title fw-bold">Academic Pages</h3>

            <div class="card-toolbar d-flex">
                {{-- SITE FILTER --}}
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
                                    <td>
                                        @if($page->page_key)
                                            <span class="badge badge-light">{{ $page->page_key }}</span>
                                        @endif
                                    </td>
                                    <td><code>{{ $page->slug }}</code></td>
                                    <td>
                                        {!! $page->is_home
                                            ? '<span class="badge badge-success">Yes</span>'
                                            : '<span class="badge badge-light">No</span>' !!}
                                    </td>
                                    <td>
                                        {!! $page->is_active
                                            ? '<span class="badge badge-success">Active</span>'
                                            : '<span class="badge badge-secondary">Inactive</span>' !!}
                                    </td>
                                    <td>{{ $page->position }}</td>
                                    <td class="text-end">

                                        @can('edit academic pages')
                                            <a href="javascript:void(0);" class="me-5"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editPageModal-{{ $page->id }}">
                                                <i class="fa-solid fa-pencil-square fs-2 text-primary"></i>
                                            </a>
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

    {{-- =========================
         CREATE PAGE MODAL
    ========================== --}}
    @if ($selectedSite)
        <div class="modal fade" id="createPageModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{ route('admin.academic.pages.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="academic_site_id" value="{{ $selectedSite->id }}">

                        <div class="modal-header">
                            <h5 class="modal-title">Create Page ({{ $selectedSite->short_name }})</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">

                            {{-- BASIC INFO --}}
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <x-metronic.label class="col-form-label fw-bold fs-6">Page Key</x-metronic.label>
                                    <input type="text" name="page_key" class="form-control form-control-sm"
                                           placeholder="about, facilities, research..."
                                           value="{{ old('page_key') }}">
                                </div>
                                <div class="col-md-3">
                                    <x-metronic.label class="col-form-label fw-bold fs-6">Slug</x-metronic.label>
                                    <input type="text" name="slug" class="form-control form-control-sm"
                                           required value="{{ old('slug') }}">
                                </div>
                                <div class="col-md-3">
                                    <x-metronic.label class="col-form-label fw-bold fs-6">Title</x-metronic.label>
                                    <input type="text" name="title" class="form-control form-control-sm"
                                           required value="{{ old('title') }}">
                                </div>
                                <div class="col-md-3">
                                    <x-metronic.label class="col-form-label fw-bold fs-6">Subtitle</x-metronic.label>
                                    <input type="text" name="subtitle" class="form-control form-control-sm"
                                           value="{{ old('subtitle') }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <x-metronic.label class="col-form-label fw-bold fs-6">Position</x-metronic.label>
                                    <input type="number" name="position" class="form-control form-control-sm"
                                           value="{{ old('position', 0) }}">
                                </div>
                                <div class="col-md-3 d-flex align-items-center">
                                    <div class="form-check mt-4">
                                        <input class="form-check-input" type="checkbox" name="is_home" value="1"
                                               id="createIsHome" {{ old('is_home') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="createIsHome">Is Home Page?</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <x-metronic.label class="col-form-label fw-bold fs-6">Active</x-metronic.label>
                                    <select name="is_active" class="form-select form-select-sm">
                                        <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ old('is_active', 1) == 0 ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                            </div>

                            {{-- HOME EXTRA FIELDS (OPTIONAL) --}}
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <x-metronic.label class="col-form-label fw-bold fs-6">Banner Title</x-metronic.label>
                                    <input type="text" name="banner_title" class="form-control form-control-sm"
                                           value="{{ old('banner_title') }}">
                                </div>
                                <div class="col-md-3">
                                    <x-metronic.label class="col-form-label fw-bold fs-6">Banner Subtitle</x-metronic.label>
                                    <input type="text" name="banner_subtitle" class="form-control form-control-sm"
                                           value="{{ old('banner_subtitle') }}">
                                </div>
                                <div class="col-md-3">
                                    <x-metronic.label class="col-form-label fw-bold fs-6">Banner Button Text</x-metronic.label>
                                    <input type="text" name="banner_button" class="form-control form-control-sm"
                                           value="{{ old('banner_button') }}">
                                </div>
                                <div class="col-md-3">
                                    <x-metronic.label class="col-form-label fw-bold fs-6">Banner Button URL</x-metronic.label>
                                    <input type="text" name="banner_button_url" class="form-control form-control-sm"
                                           value="{{ old('banner_button_url') }}">
                                </div>
                            </div>

                            {{-- IMAGES --}}
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <x-metronic.label class="col-form-label fw-bold fs-6">Banner Image</x-metronic.label>
                                    <x-metronic.image-input name="banner_image" id="bannerImageCreate" :source="''" />
                                </div>
                                <div class="col-md-6">
                                    <x-metronic.label class="col-form-label fw-bold fs-6">OG Image</x-metronic.label>
                                    <x-metronic.image-input name="og_image" id="ogImageCreate" :source="''" />
                                </div>
                            </div>

                            {{-- CONTENT --}}
                            <x-metronic.editor
                                name="content"
                                label="Page Content"
                                :value="old('content', '')"
                                rows="12" />

                            {{-- SEO --}}
                            <div class="row mt-4">
                                <div class="col-md-4">
                                    <x-metronic.label class="col-form-label fw-bold fs-6">Meta Title</x-metronic.label>
                                    <input type="text" name="meta_title" class="form-control form-control-sm"
                                           value="{{ old('meta_title') }}">
                                </div>
                                <div class="col-md-4">
                                    <x-metronic.label class="col-form-label fw-bold fs-6">Meta Tags</x-metronic.label>
                                    <input type="text" name="meta_tags" class="form-control form-control-sm"
                                           value="{{ old('meta_tags') }}">
                                </div>
                                <div class="col-md-4">
                                    <x-metronic.label class="col-form-label fw-bold fs-6">Meta Description</x-metronic.label>
                                    <textarea name="meta_description" class="form-control form-control-sm" rows="3">{{ old('meta_description') }}</textarea>
                                </div>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary btn-sm">Save Page</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- =========================
         EDIT PAGE MODALS
         (ONE PER PAGE)
    ========================== --}}
    @foreach($pages as $page)
        <div class="modal fade" id="editPageModal-{{ $page->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{ route('admin.academic.pages.update', $page->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="modal-header">
                            <h5 class="modal-title">Edit Page: {{ $page->title }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">

                            {{-- BASIC INFO --}}
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <x-metronic.label class="col-form-label fw-bold fs-6">Page Key</x-metronic.label>
                                    <input type="text" name="page_key" class="form-control form-control-sm"
                                           value="{{ old('page_key', $page->page_key) }}">
                                </div>
                                <div class="col-md-3">
                                    <x-metronic.label class="col-form-label fw-bold fs-6">Slug</x-metronic.label>
                                    <input type="text" name="slug" class="form-control form-control-sm"
                                           value="{{ old('slug', $page->slug) }}" required>
                                </div>
                                <div class="col-md-3">
                                    <x-metronic.label class="col-form-label fw-bold fs-6">Title</x-metronic.label>
                                    <input type="text" name="title" class="form-control form-control-sm"
                                           value="{{ old('title', $page->title) }}" required>
                                </div>
                                <div class="col-md-3">
                                    <x-metronic.label class="col-form-label fw-bold fs-6">Subtitle</x-metronic.label>
                                    <input type="text" name="subtitle" class="form-control form-control-sm"
                                           value="{{ old('subtitle', $page->subtitle) }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <x-metronic.label class="col-form-label fw-bold fs-6">Position</x-metronic.label>
                                    <input type="number" name="position" class="form-control form-control-sm"
                                           value="{{ old('position', $page->position) }}">
                                </div>
                                <div class="col-md-3 d-flex align-items-center">
                                    <div class="form-check mt-4">
                                        <input class="form-check-input" type="checkbox" name="is_home" value="1"
                                            id="editIsHome-{{ $page->id }}" {{ old('is_home', $page->is_home) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="editIsHome-{{ $page->id }}">
                                            Is Home Page?
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <x-metronic.label class="col-form-label fw-bold fs-6">Active</x-metronic.label>
                                    <select name="is_active" class="form-select form-select-sm">
                                        <option value="1" {{ old('is_active', $page->is_active) ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ !old('is_active', $page->is_active) ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                            </div>

                            {{-- HOME EXTRA FIELDS --}}
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <x-metronic.label class="col-form-label fw-bold fs-6">Banner Title</x-metronic.label>
                                    <input type="text" name="banner_title" class="form-control form-control-sm"
                                           value="{{ old('banner_title', $page->banner_title) }}">
                                </div>
                                <div class="col-md-3">
                                    <x-metronic.label class="col-form-label fw-bold fs-6">Banner Subtitle</x-metronic.label>
                                    <input type="text" name="banner_subtitle" class="form-control form-control-sm"
                                           value="{{ old('banner_subtitle', $page->banner_subtitle) }}">
                                </div>
                                <div class="col-md-3">
                                    <x-metronic.label class="col-form-label fw-bold fs-6">Banner Button Text</x-metronic.label>
                                    <input type="text" name="banner_button" class="form-control form-control-sm"
                                           value="{{ old('banner_button', $page->banner_button) }}">
                                </div>
                                <div class="col-md-3">
                                    <x-metronic.label class="col-form-label fw-bold fs-6">Banner Button URL</x-metronic.label>
                                    <input type="text" name="banner_button_url" class="form-control form-control-sm"
                                           value="{{ old('banner_button_url', $page->banner_button_url) }}">
                                </div>
                            </div>

                            {{-- IMAGES --}}
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <x-metronic.label class="col-form-label fw-bold fs-6">Banner Image</x-metronic.label>
                                    <x-metronic.image-input
                                        name="banner_image"
                                        id="bannerImageEdit_{{ $page->id }}"
                                        :source="!empty($page->banner_image) ? asset('storage/' . $page->banner_image) : ''" />
                                </div>
                                <div class="col-md-6">
                                    <x-metronic.label class="col-form-label fw-bold fs-6">OG Image</x-metronic.label>
                                    <x-metronic.image-input
                                        name="og_image"
                                        id="ogImageEdit_{{ $page->id }}"
                                        :source="!empty($page->og_image) ? asset('storage/' . $page->og_image) : ''" />
                                </div>
                            </div>

                            {{-- CONTENT --}}
                            <x-metronic.editor
                                name="content"
                                label="Page Content"
                                :value="old('content', $page->content)"
                                rows="12" />

                            {{-- SEO --}}
                            <div class="row mt-4">
                                <div class="col-md-4">
                                    <x-metronic.label class="col-form-label fw-bold fs-6">Meta Title</x-metronic.label>
                                    <input type="text" name="meta_title" class="form-control form-control-sm"
                                           value="{{ old('meta_title', $page->meta_title) }}">
                                </div>
                                <div class="col-md-4">
                                    <x-metronic.label class="col-form-label fw-bold fs-6">Meta Tags</x-metronic.label>
                                    <input type="text" name="meta_tags" class="form-control form-control-sm"
                                           value="{{ old('meta_tags', $page->meta_tags) }}">
                                </div>
                                <div class="col-md-4">
                                    <x-metronic.label class="col-form-label fw-bold fs-6">Meta Description</x-metronic.label>
                                    <textarea name="meta_description" class="form-control form-control-sm" rows="3">{{ old('meta_description', $page->meta_description) }}</textarea>
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
    @endforeach

    @push('scripts')
        <script>
            const csrfToken = "{{ csrf_token() }}";

            // Global delete handler (pattern you use everywhere)
            document.addEventListener('click', function (e) {
                const btn = e.target.closest('a.delete');
                if (!btn) return;

                e.preventDefault();
                const url = btn.getAttribute('href');

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This page will be permanently deleted.',
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
                            Swal.fire('Deleted!', json.message || 'Page deleted successfully.', 'success')
                                .then(() => window.location.reload());
                        } else {
                            Swal.fire('Error', json.message || 'Failed to delete page.', 'error');
                        }
                    })
                    .catch(() => Swal.fire('Error', 'Failed to delete page.', 'error'));
                });
            });
        </script>
    @endpush

</x-admin-app-layout>
