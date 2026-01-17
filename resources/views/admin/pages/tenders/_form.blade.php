@php
    $isEdit = isset($tender);
@endphp

<div class="row">
    <div class="col-lg-10 mb-10">

        <div class="border rounded-3 p-3 mb-4 bg-light">
            <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x fs-6 fw-semibold flex-nowrap overflow-auto border-0">
                <li class="nav-item">
                    <a class="nav-link pb-3 text-active-primary active" data-bs-toggle="tab" href="#tab_general">
                        <i class="fas fa-info-circle me-1 fs-6"></i>
                        General
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link pb-3 text-active-primary" data-bs-toggle="tab" href="#tab_content">
                        <i class="fas fa-align-left me-1 fs-6"></i>
                        Content
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link pb-3 text-active-primary" data-bs-toggle="tab" href="#tab_attachments">
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
            <div class="tab-pane fade show active" id="tab_general" role="tab-panel">
                <div class="card card-flush border rounded-3 shadow-none mb-6">
                    <div class="card-header border-0 pb-0">
                        <div class="card-title"><h4 class="fw-semibold mb-0">General Information</h4></div>
                    </div>
                    <div class="card-body pt-4">
                        <div class="row">
                            <div class="col-lg-6 mb-6">
                                <x-metronic.label for="title" class="col-form-label required fw-bold fs-7 text-uppercase text-muted">Title</x-metronic.label>
                                <x-metronic.input id="title" type="text" name="title" :value="old('title', $isEdit ? $tender->title : '')" required />
                                <div class="text-muted fs-8 mt-1">Frontend: tender headline/title.</div>
                            </div>

                            <div class="col-lg-6 mb-6">
                                <x-metronic.label for="reference_no" class="col-form-label fw-bold fs-7 text-uppercase text-muted">Reference No.</x-metronic.label>
                                <x-metronic.input id="reference_no" type="text" name="reference_no" :value="old('reference_no', $isEdit ? $tender->reference_no : '')" />
                                <div class="text-muted fs-8 mt-1">Frontend: tender reference shown near title.</div>
                            </div>

                            <div class="col-lg-4 mb-6">
                                <x-metronic.label for="publish_date" class="col-form-label fw-bold fs-7 text-uppercase text-muted">Publish Date</x-metronic.label>
                                <x-metronic.input id="publish_date" type="date" name="publish_date" :value="old('publish_date', $isEdit && $tender->publish_date ? $tender->publish_date->format('Y-m-d') : '')" />
                                <div class="text-muted fs-8 mt-1">Frontend: listing date (top bar).</div>
                            </div>

                            <div class="col-lg-4 mb-6">
                                <x-metronic.label for="closing_date" class="col-form-label fw-bold fs-7 text-uppercase text-muted">Closing Date</x-metronic.label>
                                <x-metronic.input id="closing_date" type="date" name="closing_date" :value="old('closing_date', $isEdit && $tender->closing_date ? $tender->closing_date->format('Y-m-d') : '')" />
                                <div class="text-muted fs-8 mt-1">Frontend: closing deadline.</div>
                            </div>

                            <div class="col-lg-4 mb-6">
                                <x-metronic.label for="department" class="col-form-label fw-bold fs-7 text-uppercase text-muted">Department / Office</x-metronic.label>
                                <x-metronic.input id="department" type="text" name="department" :value="old('department', $isEdit ? $tender->department : '')" />
                                <div class="text-muted fs-8 mt-1">Frontend: shown as issuing department.</div>
                            </div>

                            <div class="col-12 mb-6">
                                <x-metronic.label for="excerpt" class="col-form-label fw-bold fs-7 text-uppercase text-muted">Short Excerpt</x-metronic.label>
                                <textarea id="excerpt" name="excerpt" class="form-control" rows="3">{{ old('excerpt', $isEdit ? $tender->excerpt : '') }}</textarea>
                                <div class="text-muted fs-8 mt-1">Frontend: listing short summary.</div>
                            </div>

                            <div class="col-lg-4 mb-6">
                                <x-metronic.label for="status" class="col-form-label fw-bold fs-7 text-uppercase text-muted">Status</x-metronic.label>
                                @php $st = old('status', $isEdit ? $tender->status : 'published'); @endphp
                                <x-metronic.select-option id="status" name="status" data-hide-search="true">
                                    <option value="draft" {{ $st === 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ $st === 'published' ? 'selected' : '' }}>Published</option>
                                    <option value="archived" {{ $st === 'archived' ? 'selected' : '' }}>Archived</option>
                                </x-metronic.select-option>
                                <div class="text-muted fs-8 mt-1">Frontend visibility.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="tab_content" role="tab-panel">
                <div class="card card-flush border rounded-3 shadow-none mb-6">
                    <div class="card-header border-0 pb-0">
                        <div class="card-title"><h4 class="fw-semibold mb-0">Tender Content</h4></div>
                    </div>
                    <div class="card-body pt-4">
                        <x-metronic.editor name="body" label="Tender Content" :value="old('body', $isEdit ? $tender->body : '')" rows="12" />
                        <div class="text-muted fs-8 mt-1">Frontend: main tender details page body.</div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="tab_attachments" role="tab-panel">
                <div class="card card-flush border rounded-3 shadow-none mb-6">
                    <div class="card-header border-0 pb-0">
                        <div class="card-title"><h4 class="fw-semibold mb-0">Attachments</h4></div>
                    </div>
                    <div class="card-body pt-4">
                        <div class="mb-5">
                            <x-metronic.label for="attachments" class="col-form-label fw-bold fs-7 text-uppercase text-muted">Upload Files</x-metronic.label>
                            <input type="file" id="attachments" name="attachments[]" class="form-control" multiple>
                            <div class="text-muted fs-8 mt-1">Upload PDF/DOCX images. Update will overwrite existing files.</div>
                        </div>

                        @if($isEdit && is_array($tender->attachments) && count($tender->attachments))
                            <div class="mt-6">
                                <div class="fw-semibold mb-2">Current Attachments</div>
                                <ul class="mb-0 ps-4">
                                    @foreach($tender->attachments as $file)
                                        <li class="small text-muted">{{ $file }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="tab_seo" role="tab-panel">
                <div class="card card-flush border rounded-3 shadow-none mb-6">
                    <div class="card-header border-0 pb-0">
                        <div class="card-title"><h4 class="fw-semibold mb-0">SEO</h4></div>
                    </div>
                    <div class="card-body pt-4 row">
                        <div class="col-lg-6 mb-6">
                            <x-metronic.label for="meta_title" class="col-form-label fw-bold fs-7 text-uppercase text-muted">Meta Title</x-metronic.label>
                            <x-metronic.input id="meta_title" type="text" name="meta_title" :value="old('meta_title', $isEdit ? $tender->meta_title : '')" />
                        </div>
                        <div class="col-lg-6 mb-6">
                            <x-metronic.label for="meta_tags" class="col-form-label fw-bold fs-7 text-uppercase text-muted">Meta Tags</x-metronic.label>
                            <x-metronic.input id="meta_tags" type="text" name="meta_tags" :value="old('meta_tags', $isEdit ? $tender->meta_tags : '')" />
                        </div>
                        <div class="col-12 mb-6">
                            <x-metronic.label for="meta_description" class="col-form-label fw-bold fs-7 text-uppercase text-muted">Meta Description</x-metronic.label>
                            <textarea id="meta_description" name="meta_description" class="form-control" rows="3">{{ old('meta_description', $isEdit ? $tender->meta_description : '') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="col-lg-2">
        <div class="card card-flush shadow-sm">
            <div class="card-header py-5">
                <div class="card-title"><h4 class="fw-semibold mb-0">Publish</h4></div>
            </div>
            <div class="card-body pt-0">
                <div class="mb-5">
                    <x-metronic.label for="is_featured" class="col-form-label fw-bold fs-7 text-uppercase text-muted">Featured?</x-metronic.label>
                    <x-metronic.select-option id="is_featured" name="is_featured" data-hide-search="true">
                        @php $feat = old('is_featured', $isEdit ? (int)$tender->is_featured : 0); @endphp
                        <option value="0" {{ (string)$feat === '0' ? 'selected' : '' }}>No</option>
                        <option value="1" {{ (string)$feat === '1' ? 'selected' : '' }}>Yes</option>
                    </x-metronic.select-option>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-save me-1"></i>
                    {{ $isEdit ? 'Update Tender' : 'Create Tender' }}
                </button>
            </div>
        </div>
    </div>
</div>
