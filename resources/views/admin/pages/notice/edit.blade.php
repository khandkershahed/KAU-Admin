<x-admin-app-layout :title="'Edit Notice'">

    <div class="card card-flush shadow-sm">
        {{-- HEADER --}}
        <div class="card-header align-items-center py-5">
            <div class="d-flex flex-column flex-md-row justify-content-between w-100 align-items-md-center gap-3">
                <div>
                    <h3 class="card-title fw-bold mb-1">
                        Edit Notice
                    </h3>
                    <span class="text-muted fs-7">
                        Update notice details, attachments and SEO options.
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

            @php
                $existingAttachments = [];
                if (!empty($notice->attachments)) {
                    $existingAttachments = is_string($notice->attachments)
                        ? json_decode($notice->attachments, true) ?? []
                        : (is_array($notice->attachments)
                            ? $notice->attachments
                            : []);
                }
                if (!is_array($existingAttachments)) {
                    $existingAttachments = [];
                }
            @endphp

            <form method="POST" action="{{ route('admin.notice.update', $notice->id) }}" enctype="multipart/form-data">
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

                                                <select class="form-select form-select-sm" name="category_id"
                                                    id="category_id" data-control="select2"
                                                    data-placeholder="Select category">
                                                    <option value="">-- None --</option>
                                                    @foreach ($categories as $c)
                                                        <option value="{{ $c->id }}"
                                                            data-viewtype="{{ $c->view_type ?? 'page' }}"
                                                            {{ old('category_id', $notice->category_id) == $c->id ? 'selected' : '' }}>
                                                            {{ $c->name }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                            </div>

                                            <div class="col-lg-6 mb-6">
                                                <x-metronic.label for="title"
                                                    class="col-form-label required fw-bold fs-7 text-uppercase text-muted">
                                                    Title
                                                </x-metronic.label>
                                                <x-metronic.input id="title" type="text" name="title"
                                                    :value="old('title', $notice->title)" required />
                                            </div>
                                        </div>
                                        {{-- TABLE VIEW EXTRA FIELDS --}}
                                        <div id="table_fields_wrap" class="col-12" style="display:none;">
                                            <div class="separator my-6"></div>
                                            <div class="row">
                                                <div class="col-lg-4 mb-6">
                                                    <x-metronic.label for="employee_name"
                                                        class="col-form-label fw-bold fs-7 text-uppercase text-muted">
                                                        Employee Name
                                                    </x-metronic.label>
                                                    <x-metronic.input id="employee_name" type="text"
                                                        name="employee_name" :value="old('employee_name', $notice->employee_name)" />
                                                </div>

                                                <div class="col-lg-4 mb-6">
                                                    <x-metronic.label for="designation"
                                                        class="col-form-label fw-bold fs-7 text-uppercase text-muted">
                                                        Designation
                                                    </x-metronic.label>
                                                    <x-metronic.input id="designation" type="text" name="designation"
                                                        :value="old('designation', $notice->designation)" />
                                                </div>

                                                <div class="col-lg-4 mb-6">
                                                    <x-metronic.label for="department"
                                                        class="col-form-label fw-bold fs-7 text-uppercase text-muted">
                                                        Institute/Department/Section
                                                    </x-metronic.label>
                                                    <x-metronic.input id="department" type="text" name="department"
                                                        :value="old('department', $notice->department)" />
                                                </div>
                                            </div>
                                        </div>
                                        {{-- /TABLE VIEW EXTRA FIELDS --}}
                                        <div class="row">

                                            <div class="col-lg-4 mb-6">
                                                <x-metronic.label for="publish_date"
                                                    class="col-form-label fw-bold fs-7 text-uppercase text-muted">
                                                    Publish Date
                                                </x-metronic.label>
                                                <input type="date" class="form-control form-control-sm"
                                                    id="publish_date" name="publish_date"
                                                    value="{{ old('publish_date', $notice->publish_date?->format('Y-m-d')) }}">

                                                {{-- <x-metronic.input id="publish_date" type="date" name="publish_date"
                                                    :value="old('publish_date', $notice->publish_date)" /> --}}
                                            </div>

                                            <div class="col-lg-4 mb-6">
                                                <x-metronic.label for="is_featured"
                                                    class="col-form-label fw-bold fs-7 text-uppercase text-muted">
                                                    Featured?
                                                </x-metronic.label>
                                                <x-metronic.select-option id="is_featured" name="is_featured"
                                                    data-hide-search="true">
                                                    <option value="0"
                                                        {{ old('is_featured', (string) $notice->is_featured) === '0' ? 'selected' : '' }}>
                                                        No</option>
                                                    <option value="1"
                                                        {{ old('is_featured', (string) $notice->is_featured) === '1' ? 'selected' : '' }}>
                                                        Yes</option>
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
                                                        {{ old('status', $notice->status) === 'draft' ? 'selected' : '' }}>
                                                        Draft
                                                    </option>
                                                    <option value="published"
                                                        {{ old('status', $notice->status) === 'published' ? 'selected' : '' }}>
                                                        Published
                                                    </option>
                                                    <option value="archived"
                                                        {{ old('status', $notice->status) === 'archived' ? 'selected' : '' }}>
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
                                        <x-metronic.editor name="body" label="Notice Content" :value="old('body', $notice->body)"
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

                                        {{-- EXISTING ATTACHMENTS --}}
                                        @if (!empty($existingAttachments))
                                            <div class="mb-6">
                                                <div class="fw-semibold mb-3">Existing Attachments</div>

                                                <div class="d-flex flex-wrap gap-3">
                                                    @foreach ($existingAttachments as $path)
                                                        @php
                                                            $pathStr = is_string($path) ? $path : '';
                                                            $fileName = $pathStr ? basename($pathStr) : 'file';
                                                            $url = $pathStr
                                                                ? asset('storage/' . ltrim($pathStr, '/'))
                                                                : '#';
                                                        @endphp

                                                        <div class="border rounded-3 p-3 bg-light"
                                                            style="min-width: 220px;">
                                                            <div class="d-flex align-items-center gap-2 mb-2">
                                                                <i class="fas fa-file-alt text-primary"></i>
                                                                <div class="fw-semibold text-truncate"
                                                                    style="max-width: 170px;">
                                                                    {{ $fileName }}
                                                                </div>
                                                            </div>
                                                            <a href="{{ $url }}" target="_blank"
                                                                class="btn btn-sm btn-light-primary w-100">
                                                                View / Download
                                                            </a>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif

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
                                        <div class="col-lg-5 mb-6">
                                            <x-metronic.label for="meta_title"
                                                class="col-form-label fw-bold fs-7 text-uppercase text-muted">
                                                Meta Title
                                            </x-metronic.label>
                                            <x-metronic.input id="meta_title" type="text" name="meta_title"
                                                :value="old('meta_title', $notice->meta_title)" />
                                        </div>

                                        <div class="col-lg-7 mb-6">
                                            <x-metronic.label for="meta_tags"
                                                class="col-form-label fw-bold fs-7 text-uppercase text-muted">
                                                Meta Tags
                                            </x-metronic.label>
                                            <x-metronic.input id="meta_tags" type="text" name="meta_tags"
                                                :value="old('meta_tags', $notice->meta_tags)" placeholder="notice, admission,.." />
                                        </div>

                                        <div class="col-lg-12 mb-6">
                                            <x-metronic.label for="meta_description"
                                                class="col-form-label fw-bold fs-7 text-uppercase text-muted">
                                                Meta Description
                                            </x-metronic.label>
                                            <x-metronic.textarea id="meta_description" name="meta_description"
                                                placeholder="Short description">{{ old('meta_description', $notice->meta_description) }}</x-metronic.textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

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

                    {{-- RIGHT COLUMN --}}
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
                </div>
            </form>
        </div>
    </div>

    {{-- SCRIPT: show/hide fields when category view_type=table --}}
    @push('scripts')
        <script>
            $(document).ready(function() {
                const categoryEl = $('#category_id');
                const tableFieldsWrap = $('#table_fields_wrap');

                if (!categoryEl.length || !tableFieldsWrap.length) return;

                function toggleTableFields() {
                    const selectedOption = categoryEl.find('option:selected');

                    if (!selectedOption.length) {
                        tableFieldsWrap.hide();
                        return;
                    }

                    const viewType = selectedOption.data('viewtype') || 'page';

                    if (viewType === 'table') {
                        tableFieldsWrap.show();
                    } else {
                        tableFieldsWrap.hide();
                    }
                }

                categoryEl.on('change', toggleTableFields);

                // IMPORTANT: run once on page load (edit mode)
                setTimeout(toggleTableFields, 50);
            });
        </script>
    @endpush


</x-admin-app-layout>
