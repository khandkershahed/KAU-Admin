<x-admin-app-layout :title="'Edit About Page'">

    <form action="{{ route('admin.about.update', $page->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card">
            <div class="card-header">
                <h3 class="card-title fw-bold">Edit About Page</h3>
            </div>

            <div class="card-body row g-5">

                <div class="col-md-8">
                    <div class="mb-5">
                        <x-metronic.label class="required">Title</x-metronic.label>
                        <input type="text" name="title" value="{{ old('title', $page->title) }}"
                            class="form-control form-control-sm" required>
                    </div>

                    {{-- <div class="mb-5">
                        <x-metronic.label>Slug (optional)</x-metronic.label>
                        <input type="text" name="slug" value="{{ old('slug', $page->slug) }}"
                            class="form-control form-control-sm">
                        <small class="text-muted">Leave empty to keep current / auto from title.</small>
                    </div> --}}

                    {{-- <div class="mb-5">
                        <x-metronic.label>Excerpt</x-metronic.label>
                        <textarea name="excerpt" class="form-control" rows="3">{{ old('excerpt', $page->excerpt) }}</textarea>
                    </div> --}}

                    <div class="mb-5">
                        <x-metronic.editor name="content" label="About Page Content" :value="old('content', $page->content)" rows="12" />
                    </div>
                </div>

                <div class="col-md-4">

                    <div class="mb-5">
                        <x-metronic.label>Menu Label</x-metronic.label>
                        <input type="text" name="menu_label" value="{{ old('menu_label', $page->menu_label) }}"
                            class="form-control form-control-sm">
                    </div>

                    {{-- <div class="mb-5">
                        <x-metronic.label>Banner Title</x-metronic.label>
                        <input type="text" name="banner_title" value="{{ old('banner_title', $page->banner_title) }}"
                            class="form-control form-control-sm">
                    </div>

                    <div class="mb-5">
                        <x-metronic.label>Banner Subtitle</x-metronic.label>
                        <input type="text" name="banner_subtitle"
                            value="{{ old('banner_subtitle', $page->banner_subtitle) }}"
                            class="form-control form-control-sm">
                    </div>

                    <div class="mb-5">
                        <x-metronic.label>Banner Icon (FontAwesome class)</x-metronic.label>
                        <input type="text" name="banner_icon"
                            value="{{ old('banner_icon', $page->banner_icon ?: 'fa-solid fa-graduation-cap') }}"
                            class="form-control form-control-sm">
                    </div> --}}

                    <div class="mb-5">
                        <x-metronic.label>Banner Image</x-metronic.label>
                        <input type="file" name="banner_image" class="form-control form-control-sm">
                        @if ($page->banner_image)
                            <small class="text-muted d-block mt-1">
                                Current: {{ $page->banner_image }}
                            </small>
                        @endif
                    </div>

                    <div class="mb-5">
                        <x-metronic.label>Status</x-metronic.label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="draft" {{ old('status', $page->status) === 'draft' ? 'selected' : '' }}>
                                Draft</option>
                            <option value="published"
                                {{ old('status', $page->status) === 'published' ? 'selected' : '' }}>Published</option>
                            <option value="archived"
                                {{ old('status', $page->status) === 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                    </div>

                    {{-- <div class="mb-5">
                        <div class="form-check form-switch form-check-custom form-check-solid">
                            <input class="form-check-input" type="checkbox" value="1" id="is_featured"
                                name="is_featured" {{ old('is_featured', $page->is_featured) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">
                                Featured on About page
                            </label>
                        </div>
                    </div> --}}

                    <div class="mb-5">
                        <x-metronic.label>Meta Title</x-metronic.label>
                        <input type="text" name="meta_title" value="{{ old('meta_title', $page->meta_title) }}"
                            class="form-control form-control-sm">
                    </div>

                    <div class="mb-5">
                        <x-metronic.label>Meta Tags</x-metronic.label>
                        <input type="text" name="meta_tags" placeholder="example1,example2,example3,..." value="{{ old('meta_tags', $page->meta_tags) }}"
                            class="form-control form-control-sm">
                    </div>

                    <div class="mb-5">
                        <x-metronic.label>Meta Description</x-metronic.label>
                        <textarea name="meta_description" class="form-control" rows="3">{{ old('meta_description', $page->meta_description) }}</textarea>
                    </div>
                </div>

            </div>

            <div class="card-footer d-flex justify-content-end">
                <a href="{{ route('admin.about.index') }}" class="btn btn-light me-3">Back</a>
                @can('edit about page')
                    <button class="btn btn-primary">Update</button>
                @endcan
            </div>
        </div>

    </form>

</x-admin-app-layout>
