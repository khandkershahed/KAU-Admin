<x-admin-app-layout :title="'Create Notice'">

    <div class="card card-flash">
        <div class="mt-6 card-header">
            <div class="card-toolbar">
                <a href="{{ route('admin.notice.index') }}" class="btn btn-light-info">
                    <span class="svg-icon svg-icon-3"><i class="fas fa-arrow-left"></i></span>
                    Back to the list
                </a>
            </div>
        </div>

        <div class="pt-0 card-body">
            <form method="POST" action="{{ route('admin.notice.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-8 gap-7 gap-lg-10">
                        <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x fs-4 fw-semibold mb-n2 border-0">
                            <li class="nav-item">
                                <a class="nav-link pb-4 text-active-primary active" data-bs-toggle="tab"
                                    href="#tab_general">General</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link pb-4 text-active-primary" data-bs-toggle="tab"
                                    href="#tab_content">Content</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link pb-4 text-active-primary" data-bs-toggle="tab"
                                    href="#tab_attachments">Attachments</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link pb-4 text-active-primary" data-bs-toggle="tab"
                                    href="#tab_seo">SEO</a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            {{-- General --}}
                            <div class="tab-pane fade show active" id="tab_general" role="tab-panel">
                                <div class="d-flex flex-column gap-7 gap-lg-10">
                                    <div class="card card-flush mt-3 py-4">
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h2>General</h2>
                                            </div>
                                        </div>
                                        <div class="card-body pt-0">
                                            <div class="row">
                                                <div class="col-lg-6 mb-7">
                                                    <x-metronic.label for="category_id"
                                                        class="col-form-label fw-bold fs-6">
                                                        Category
                                                    </x-metronic.label>
                                                    <x-metronic.select-option id="category_id" name="category_id"
                                                        data-hide-search="false" data-placeholder="Select category">
                                                        <option value="">-- None --</option>
                                                        @foreach ($categories as $c)
                                                            <option value="{{ $c->id }}"
                                                                {{ old('category_id') == $c->id ? 'selected' : '' }}>
                                                                {{ $c->name }}
                                                            </option>
                                                        @endforeach
                                                    </x-metronic.select-option>
                                                </div>

                                                <div class="col-lg-6 mb-7">
                                                    <x-metronic.label for="title"
                                                        class="col-form-label required fw-bold fs-6">
                                                        Title
                                                    </x-metronic.label>
                                                    <x-metronic.input id="title" type="text" name="title"
                                                        :value="old('title')" required />
                                                </div>

                                                <div class="col-lg-4 mb-7">
                                                    <x-metronic.label for="publish_date"
                                                        class="col-form-label fw-bold fs-6">
                                                        Publish Date
                                                    </x-metronic.label>
                                                    <x-metronic.input id="publish_date" type="date"
                                                        name="publish_date" :value="old('publish_date')" />
                                                </div>

                                                <div class="col-lg-4 mb-7">
                                                    <x-metronic.label for="is_featured"
                                                        class="col-form-label fw-bold fs-6">
                                                        Featured?
                                                    </x-metronic.label>
                                                    <x-metronic.select-option id="is_featured" name="is_featured"
                                                        data-hide-search="true">
                                                        <option value="0"
                                                            {{ old('is_featured') == '0' ? 'selected' : '' }}>No
                                                        </option>
                                                        <option value="1"
                                                            {{ old('is_featured') == '1' ? 'selected' : '' }}>Yes
                                                        </option>
                                                    </x-metronic.select-option>
                                                </div>

                                                <div class="col-lg-4 mb-7">
                                                    <x-metronic.label for="status"
                                                        class="col-form-label fw-bold fs-6">
                                                        Status
                                                    </x-metronic.label>
                                                    <x-metronic.select-option id="status" name="status"
                                                        data-hide-search="true">
                                                        <option value="draft"
                                                            {{ old('status', 'published') === 'draft' ? 'selected' : '' }}>
                                                            Draft</option>
                                                        <option value="published"
                                                            {{ old('status', 'published') === 'published' ? 'selected' : '' }}>
                                                            Published</option>
                                                        <option value="archived"
                                                            {{ old('status') === 'archived' ? 'selected' : '' }}>
                                                            Archived</option>
                                                    </x-metronic.select-option>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Content --}}
                            <div class="tab-pane fade" id="tab_content" role="tab-panel">
                                <div class="d-flex flex-column gap-7 gap-lg-10">
                                    <div class="card card-flush mt-3 py-4">
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h2>Notice Content</h2>
                                            </div>
                                        </div>
                                        <div class="card-body pt-0">
                                            <x-metronic.editor name="body" label="Body"
                                                :value="old('body')" rows="15" />
                                            <div class="text-muted fs-7">
                                                Main notice body content (HTML allowed).
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Attachments --}}
                            <div class="tab-pane fade" id="tab_attachments" role="tab-panel">
                                <div class="d-flex flex-column gap-7 gap-lg-10">
                                    <div class="card card-flush mt-3 py-4">
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h2>Attachments</h2>
                                            </div>
                                        </div>
                                        <div class="card-body pt-0">
                                            <div class="mb-5">
                                                <x-metronic.label for="attachments"
                                                    class="col-form-label fw-bold fs-6">
                                                    Upload Files
                                                </x-metronic.label>
                                                <input type="file" id="attachments" name="attachments[]"
                                                    class="form-control" multiple>
                                                <div class="text-muted fs-7">
                                                    You can upload multiple files (PDF, DOCX, JPG, etc.).
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- SEO --}}
                            <div class="tab-pane fade" id="tab_seo" role="tab-panel">
                                <div class="d-flex flex-column gap-7 gap-lg-10">
                                    <div class="card card-flush mt-3 py-4">
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h2>SEO</h2>
                                            </div>
                                        </div>
                                        <div class="card-body pt-0 row">
                                            <div class="col-lg-4 mb-7">
                                                <x-metronic.label for="meta_title"
                                                    class="col-form-label fw-bold fs-6">
                                                    Meta Title
                                                </x-metronic.label>
                                                <x-metronic.input id="meta_title" type="text"
                                                    name="meta_title" :value="old('meta_title')" />
                                            </div>

                                            <div class="col-lg-4 mb-7">
                                                <x-metronic.label for="meta_tags"
                                                    class="col-form-label fw-bold fs-6">
                                                    Meta Tags
                                                </x-metronic.label>
                                                <x-metronic.input id="meta_tags" type="text"
                                                    name="meta_tags" :value="old('meta_tags')"
                                                    placeholder="notice, admission" />
                                            </div>

                                            <div class="col-lg-4 mb-7">
                                                <x-metronic.label for="meta_description"
                                                    class="col-form-label fw-bold fs-6">
                                                    Meta Description
                                                </x-metronic.label>
                                                <x-metronic.textarea id="meta_description"
                                                    name="meta_description"
                                                    placeholder="Short description">{{ old('meta_description') }}</x-metronic.textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="d-flex justify-content-end mt-10">
                            <a href="{{ route('admin.notice.index') }}" class="btn btn-danger me-5">
                                Back To Notices List
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <span class="indicator-label">Save Notice</span>
                            </button>
                        </div>
                    </div>

                    {{-- Right side small card for Status & Featured moved to General tab already --}}
                    <div class="col-4 gap-7 gap-lg-10 mb-7">
                        {{-- You can keep extra cards here later if needed --}}
                    </div>
                </div>

            </form>
        </div>
    </div>

</x-admin-app-layout>
