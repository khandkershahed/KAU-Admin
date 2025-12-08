<x-admin-app-layout :title="'Create Notice'">

    <div class="card card-flush shadow-sm">
        {{-- HEADER --}}
        <div class="card-header align-items-center py-5">
            <div class="d-flex flex-column flex-md-row justify-content-between w-100 align-items-md-center gap-3">
                <div>
                    <h3 class="card-title fw-bold mb-1">
                        Create Notice
                    </h3>
                    <span class="text-muted fs-7">
                        Add a new notice with content, attachments and SEO options.
                    </span>
                </div>

                <div class="card-toolbar">
                    <a href="{{ route('admin.notice.index') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>
                        Back to the list
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body pt-0">
            {{-- GLOBAL ALERTS --}}
            @if ($errors->any())
                <div class="alert alert-danger mb-6">
                    <div class="d-flex flex-column">
                        <h5 class="mb-1">Please fix the following errors:</h5>
                        <ul class="mb-0 ps-4">
                            @foreach ($errors->all() as $error)
                                <li class="small">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.notice.update',$notice->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    {{-- MAIN COLUMN --}}
                    <div class="col-lg-10 mb-10">
                        {{-- TABS HEADER --}}
                        <div class="border rounded-3 p-3 mb-4 bg-light">
                            <ul
                                class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x fs-6 fw-semibold flex-nowrap overflow-auto border-0">
                                <li class="nav-item">
                                    <a class="nav-link pb-3 text-active-primary active" data-bs-toggle="tab"
                                        href="#tab_general">
                                        <i class="fas fa-info-circle me-1 fs-6"></i>
                                        General
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link pb-3 text-active-primary" data-bs-toggle="tab"
                                        href="#tab_content">
                                        <i class="fas fa-align-left me-1 fs-6"></i>
                                        Content
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link pb-3 text-active-primary" data-bs-toggle="tab"
                                        href="#tab_attachments">
                                        <i class="fas fa-paperclip me-1 fs-6"></i>
                                        Attachments
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link pb-3 text-active-primary" data-bs-toggle="tab" href="#tab_seo">
                                        <i class="fas fa-search me-1 fs-6"></i>
                                        SEO
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="tab-content">
                            {{-- GENERAL --}}
                            <div class="tab-pane fade show active" id="tab_general" role="tab-panel">
                                <div class="card card-flush border rounded-3 shadow-none mb-6">
                                    <div class="card-header border-0 pb-0">
                                        <div class="card-title">
                                            <h4 class="fw-semibold mb-0">General Information</h4>
                                        </div>
                                    </div>
                                    <div class="card-body pt-4">
                                        <div class="row">
                                            <div class="col-lg-6 mb-6">
                                                <x-metronic.label for="category_id"
                                                    class="col-form-label fw-bold fs-7 text-uppercase text-muted">
                                                    Category
                                                </x-metronic.label>
                                                <x-metronic.select-option id="category_id" name="category_id"
                                                    data-hide-search="false" data-placeholder="Select category">
                                                    <option value="">-- None --</option>
                                                    @foreach ($categories as $c)
                                                        <option value="{{ $c->id }}"
                                                            {{ old('category_id',$notice->category_id) == $c->id ? 'selected' : '' }}>
                                                            {{ $c->name }}
                                                        </option>
                                                    @endforeach
                                                </x-metronic.select-option>
                                            </div>

                                            <div class="col-lg-6 mb-6">
                                                <x-metronic.label for="title"
                                                    class="col-form-label required fw-bold fs-7 text-uppercase text-muted">
                                                    Title
                                                </x-metronic.label>
                                                <x-metronic.input id="title" type="text" name="title"
                                                    :value="old('title',$notice->title)" required />
                                            </div>

                                            <div class="col-lg-4 mb-6">
                                                <x-metronic.label for="publish_date"
                                                    class="col-form-label fw-bold fs-7 text-uppercase text-muted">
                                                    Publish Date
                                                </x-metronic.label>
                                                <x-metronic.input id="publish_date" type="date" name="publish_date"
                                                    :value="old('publish_date',$notice->publish_date)" />
                                            </div>

                                            <div class="col-lg-4 mb-6">
                                                <x-metronic.label for="is_featured"
                                                    class="col-form-label fw-bold fs-7 text-uppercase text-muted">
                                                    Featured?
                                                </x-metronic.label>
                                                <x-metronic.select-option id="is_featured" name="is_featured"
                                                    data-hide-search="true">
                                                    <option value="0"
                                                        {{ old('is_featured') == '0' ? 'selected' : '' }}>No</option>
                                                    <option value="1"
                                                        {{ old('is_featured') == '1' ? 'selected' : '' }}>Yes</option>
                                                </x-metronic.select-option>
                                            </div>

                                            <div class="col-lg-4 mb-6">
                                                <x-metronic.label for="status"
                                                    class="col-form-label fw-bold fs-7 text-uppercase text-muted">
                                                    Status
                                                </x-metronic.label>
                                                <x-metronic.select-option id="status" name="status"
                                                    data-hide-search="true">
                                                    <option value="draft"
                                                        {{ old('status', 'published') === 'draft' ? 'selected' : '' }}>
                                                        Draft
                                                    </option>
                                                    <option value="published"
                                                        {{ old('status', 'published') === 'published' ? 'selected' : '' }}>
                                                        Published
                                                    </option>
                                                    <option value="archived"
                                                        {{ old('status') === 'archived' ? 'selected' : '' }}>
                                                        Archived
                                                    </option>
                                                </x-metronic.select-option>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- CONTENT --}}
                            <div class="tab-pane fade" id="tab_content" role="tab-panel">
                                <div class="card card-flush border rounded-3 shadow-none mb-6">
                                    <div class="card-header border-0 pb-0">
                                        <div class="card-title">
                                            <h4 class="fw-semibold mb-0">Notice Content</h4>
                                        </div>
                                    </div>
                                    <div class="card-body pt-4">
                                        <x-metronic.editor name="body" label="Notice Content" :value="old('body')"
                                            rows="12" />
                                        <div class="text-muted fs-8 mt-1">
                                            Main notice body content. You can use formatting, links and attachments.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- ATTACHMENTS --}}
                            <div class="tab-pane fade" id="tab_attachments" role="tab-panel">
                                <div class="card card-flush border rounded-3 shadow-none mb-6">
                                    <div class="card-header border-0 pb-0">
                                        <div class="card-title">
                                            <h4 class="fw-semibold mb-0">Attachments</h4>
                                        </div>
                                    </div>
                                    <div class="card-body pt-4">
                                        <div class="mb-5">
                                            <x-metronic.label for="attachments"
                                                class="col-form-label fw-bold fs-7 text-uppercase text-muted">
                                                Upload Files
                                            </x-metronic.label>
                                            <input type="file" id="attachments" name="attachments[]"
                                                class="form-control" multiple>
                                            <div class="text-muted fs-8 mt-1">
                                                You can upload multiple files (PDF, DOCX, JPG, etc.).
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- SEO --}}
                            <div class="tab-pane fade" id="tab_seo" role="tab-panel">
                                <div class="card card-flush border rounded-3 shadow-none mb-6">
                                    <div class="card-header border-0 pb-0">
                                        <div class="card-title">
                                            <h4 class="fw-semibold mb-0">SEO</h4>
                                        </div>
                                    </div>
                                    <div class="card-body pt-4 row">
                                        <div class="col-lg-4 mb-6">
                                            <x-metronic.label for="meta_title"
                                                class="col-form-label fw-bold fs-7 text-uppercase text-muted">
                                                Meta Title
                                            </x-metronic.label>
                                            <x-metronic.input id="meta_title" type="text" name="meta_title"
                                                :value="old('meta_title')" />
                                        </div>

                                        <div class="col-lg-4 mb-6">
                                            <x-metronic.label for="meta_tags"
                                                class="col-form-label fw-bold fs-7 text-uppercase text-muted">
                                                Meta Tags
                                            </x-metronic.label>
                                            <x-metronic.input id="meta_tags" type="text" name="meta_tags"
                                                :value="old('meta_tags')" placeholder="notice, admission" />
                                        </div>

                                        <div class="col-lg-4 mb-6">
                                            <x-metronic.label for="meta_description"
                                                class="col-form-label fw-bold fs-7 text-uppercase text-muted">
                                                Meta Description
                                            </x-metronic.label>
                                            <x-metronic.textarea id="meta_description" name="meta_description"
                                                placeholder="Short description">{{ old('meta_description') }}</x-metronic.textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> {{-- /tab-content --}}

                        {{-- ACTIONS --}}
                        <div class="d-flex justify-content-end mt-8">
                            <a href="{{ route('admin.notice.index') }}" class="btn btn-light-danger me-3">
                                Back To Notices List
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <span class="indicator-label">Save Notice</span>
                            </button>
                        </div>
                    </div>

                    {{-- RIGHT COLUMN (optional helper card) --}}
                    <div class="col-lg-2 mb-10">
                        <div class="card border-dashed rounded-3">
                            <div class="card-body py-4">
                                <div class="fw-semibold fs-8 text-muted text-uppercase mb-1">
                                    Publishing Tips
                                </div>
                                <div class="text-muted fs-8">
                                    Choose a clear title and category. Use <strong>Published</strong> status only when
                                    the notice is ready for students and staff.
                                </div>
                            </div>
                        </div>
                    </div>
                </div> {{-- /row --}}
            </form>
        </div>
    </div>

</x-admin-app-layout>
