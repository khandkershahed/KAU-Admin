<x-admin-app-layout :title="'Create About Page'">

    <form action="{{ route('admin.about.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="card">
            <div class="card-header">
                <h3 class="card-title fw-bold">Create About Page</h3>
            </div>

            <div class="card-body row g-5">

                <div class="col-md-8">
                    <div class="mb-5">
                        <x-metronic.label class="required">Title</x-metronic.label>
                        <input type="text" name="title" value="{{ old('title') }}"
                            class="form-control form-control-sm" required>
                    </div>

                    {{-- <div class="mb-5">
                        <x-metronic.label>Slug (optional)</x-metronic.label>
                        <input type="text" name="slug" value="{{ old('slug') }}"
                            class="form-control form-control-sm">
                        <small class="text-muted">Leave empty to auto-generate from title.</small>
                    </div> --}}

                    {{-- <div class="mb-5">
                        <x-metronic.label>Excerpt</x-metronic.label>
                        <textarea name="excerpt" class="form-control" rows="3">{{ old('excerpt') }}</textarea>
                    </div> --}}

                    <div class="mb-5">
                        <x-metronic.label>Content</x-metronic.label>
                        <x-metronic.editor name="content" label="Page Content" :value="old('content')" rows="12" />
                    </div>
                </div>

                <div class="col-md-4">

                    <div class="mb-5">
                        <x-metronic.label>Menu Label</x-metronic.label>
                        <input type="text" name="menu_label" value="{{ old('menu_label') }}"
                            class="form-control form-control-sm">
                    </div>

                    {{-- <div class="mb-5">
                        <x-metronic.label>Banner Title</x-metronic.label>
                        <input type="text" name="banner_title" value="{{ old('banner_title') }}"
                            class="form-control form-control-sm">
                    </div>

                    <div class="mb-5">
                        <x-metronic.label>Banner Subtitle</x-metronic.label>
                        <input type="text" name="banner_subtitle" value="{{ old('banner_subtitle') }}"
                            class="form-control form-control-sm">
                    </div>

                    <div class="mb-5">
                        <x-metronic.label>Banner Icon (FontAwesome class)</x-metronic.label>
                        <input type="text" name="banner_icon"
                            value="{{ old('banner_icon', 'fa-solid fa-graduation-cap') }}"
                            class="form-control form-control-sm">
                    </div> --}}

                    <div class="mb-5">
                        <x-metronic.label>Banner Image</x-metronic.label>
                        <input type="file" name="banner_image" class="form-control form-control-sm">
                    </div>

                    <div class="mb-5">
                        <x-metronic.label>Status</x-metronic.label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published"
                                {{ old('status', 'published') === 'published' ? 'selected' : '' }}>Published</option>
                            <option value="archived" {{ old('status') === 'archived' ? 'selected' : '' }}>Archived
                            </option>
                        </select>
                    </div>

                    {{-- <div class="mb-5">
                        <div class="form-check form-switch form-check-custom form-check-solid">
                            <input class="form-check-input" type="checkbox" value="1" id="is_featured"
                                name="is_featured" {{ old('is_featured') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">
                                Featured on About page
                            </label>
                        </div>
                    </div> --}}

                    <div class="mb-5">
                        <x-metronic.label>Meta Title</x-metronic.label>
                        <input type="text" name="meta_title" value="{{ old('meta_title') }}"
                            class="form-control form-control-sm">
                    </div>

                    <div class="mb-5">
                        <x-metronic.label>Meta Tags</x-metronic.label>
                        <input type="text" placeholder="example1,example2,example3,..." name="meta_tags" value="{{ old('meta_tags') }}"
                            class="form-control form-control-sm">
                    </div>

                    <div class="mb-5">
                        <x-metronic.label>Meta Description</x-metronic.label>
                        <textarea name="meta_description" class="form-control" rows="3">{{ old('meta_description') }}</textarea>
                    </div>
                </div>

            </div>

            <div class="card-footer d-flex justify-content-end">
                <a href="{{ route('admin.about.index') }}" class="btn btn-light me-3">Cancel</a>
                <button class="btn btn-primary">Save</button>
            </div>
        </div>

    </form>

</x-admin-app-layout>
