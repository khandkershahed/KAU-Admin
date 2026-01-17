@php
    $isEdit = isset($event);
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
                    <a class="nav-link pb-3 text-active-primary" data-bs-toggle="tab" href="#tab_schedule">
                        <i class="fas fa-calendar-alt me-1 fs-6"></i>
                        Schedule
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link pb-3 text-active-primary" data-bs-toggle="tab" href="#tab_content">
                        <i class="fas fa-align-left me-1 fs-6"></i>
                        Content
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link pb-3 text-active-primary" data-bs-toggle="tab" href="#tab_media">
                        <i class="fas fa-image me-1 fs-6"></i>
                        Media
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
                        <div class="card-title">
                            <h4 class="fw-semibold mb-0">General Information</h4>
                        </div>
                    </div>
                    <div class="card-body pt-4">
                        <div class="row">
                            <div class="col-lg-8 mb-6">
                                <x-metronic.label for="title" class="col-form-label required fw-bold fs-7 text-uppercase text-muted">Title</x-metronic.label>
                                <x-metronic.input id="title" type="text" name="title" :value="old('title', $isEdit ? $event->title : '')" required />
                                <div class="text-muted fs-8 mt-1">This will show as the event heading on the frontend.</div>
                            </div>

                            <div class="col-lg-4 mb-6">
                                <x-metronic.label for="status" class="col-form-label fw-bold fs-7 text-uppercase text-muted">Status</x-metronic.label>
                                <x-metronic.select-option id="status" name="status" data-hide-search="true">
                                    @php $st = old('status', $isEdit ? $event->status : 'published'); @endphp
                                    <option value="draft" {{ $st === 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ $st === 'published' ? 'selected' : '' }}>Published</option>
                                    <option value="archived" {{ $st === 'archived' ? 'selected' : '' }}>Archived</option>
                                </x-metronic.select-option>
                            </div>

                            <div class="col-lg-12 mb-6">
                                <x-metronic.label for="excerpt" class="col-form-label fw-bold fs-7 text-uppercase text-muted">Short Summary</x-metronic.label>
                                <textarea id="excerpt" name="excerpt" class="form-control" rows="3">{{ old('excerpt', $isEdit ? $event->excerpt : '') }}</textarea>
                                <div class="text-muted fs-8 mt-1">Optional short intro shown in listing cards.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="tab_schedule" role="tab-panel">
                <div class="card card-flush border rounded-3 shadow-none mb-6">
                    <div class="card-header border-0 pb-0">
                        <div class="card-title">
                            <h4 class="fw-semibold mb-0">Schedule & Contact</h4>
                        </div>
                    </div>
                    <div class="card-body pt-4">
                        <div class="row">
                            <div class="col-lg-6 mb-6">
                                <x-metronic.label for="start_at" class="col-form-label fw-bold fs-7 text-uppercase text-muted">Start Date & Time</x-metronic.label>
                                <x-metronic.input id="start_at" type="datetime-local" name="start_at" :value="old('start_at', $isEdit && $event->start_at ? $event->start_at->format('Y-m-d\TH:i') : '')" />
                                <div class="text-muted fs-8 mt-1">Used for event listing and detail page schedule.</div>
                            </div>

                            <div class="col-lg-6 mb-6">
                                <x-metronic.label for="end_at" class="col-form-label fw-bold fs-7 text-uppercase text-muted">End Date & Time</x-metronic.label>
                                <x-metronic.input id="end_at" type="datetime-local" name="end_at" :value="old('end_at', $isEdit && $event->end_at ? $event->end_at->format('Y-m-d\TH:i') : '')" />
                            </div>

                            <div class="col-lg-6 mb-6">
                                <x-metronic.label for="venue" class="col-form-label fw-bold fs-7 text-uppercase text-muted">Venue</x-metronic.label>
                                <x-metronic.input id="venue" type="text" name="venue" :value="old('venue', $isEdit ? $event->venue : '')" />
                            </div>

                            <div class="col-lg-6 mb-6">
                                <x-metronic.label for="organizer" class="col-form-label fw-bold fs-7 text-uppercase text-muted">Organizer</x-metronic.label>
                                <x-metronic.input id="organizer" type="text" name="organizer" :value="old('organizer', $isEdit ? $event->organizer : '')" />
                            </div>

                            <div class="col-lg-4 mb-6">
                                <x-metronic.label for="contact_email" class="col-form-label fw-bold fs-7 text-uppercase text-muted">Contact Email</x-metronic.label>
                                <x-metronic.input id="contact_email" type="email" name="contact_email" :value="old('contact_email', $isEdit ? $event->contact_email : '')" />
                            </div>

                            <div class="col-lg-4 mb-6">
                                <x-metronic.label for="contact_phone" class="col-form-label fw-bold fs-7 text-uppercase text-muted">Contact Phone</x-metronic.label>
                                <x-metronic.input id="contact_phone" type="text" name="contact_phone" :value="old('contact_phone', $isEdit ? $event->contact_phone : '')" />
                            </div>

                            <div class="col-lg-4 mb-6">
                                <x-metronic.label for="registration_url" class="col-form-label fw-bold fs-7 text-uppercase text-muted">Registration URL</x-metronic.label>
                                <x-metronic.input id="registration_url" type="text" name="registration_url" :value="old('registration_url', $isEdit ? $event->registration_url : '')" />
                                <div class="text-muted fs-8 mt-1">Optional link for registration/button on the frontend.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="tab_content" role="tab-panel">
                <div class="card card-flush border rounded-3 shadow-none mb-6">
                    <div class="card-header border-0 pb-0">
                        <div class="card-title">
                            <h4 class="fw-semibold mb-0">Event Description</h4>
                        </div>
                    </div>
                    <div class="card-body pt-4">
                        <x-metronic.editor name="body" label="Event Details" :value="old('body', $isEdit ? $event->body : '')" rows="12" />
                        <div class="text-muted fs-8 mt-1">Main event content (what, who, agenda, etc.).</div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="tab_media" role="tab-panel">
                <div class="card card-flush border rounded-3 shadow-none mb-6">
                    <div class="card-header border-0 pb-0">
                        <div class="card-title">
                            <h4 class="fw-semibold mb-0">Banner Image</h4>
                        </div>
                    </div>
                    <div class="card-body pt-4">
                        <x-metronic.image-input name="banner_image" :value="($isEdit ? $event->banner_image : null)" />
                        <div class="text-muted fs-8 mt-1">Shown on event detail page header (optional).</div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="tab_attachments" role="tab-panel">
                <div class="card card-flush border rounded-3 shadow-none mb-6">
                    <div class="card-header border-0 pb-0">
                        <div class="card-title">
                            <h4 class="fw-semibold mb-0">Attachments</h4>
                        </div>
                    </div>
                    <div class="card-body pt-4">
                        <x-metronic.label for="attachments" class="col-form-label fw-bold fs-7 text-uppercase text-muted">Upload Files</x-metronic.label>
                        <input type="file" id="attachments" name="attachments[]" class="form-control" multiple>
                        <div class="text-muted fs-8 mt-1">Upload brochures, schedules, PDFs, images, etc. Uploading new files will replace old ones.</div>

                        @if($isEdit && is_array($event->attachments) && count($event->attachments))
                            <div class="mt-4">
                                <div class="fw-semibold mb-2">Current Files</div>
                                <ul class="mb-0 ps-4">
                                    @foreach($event->attachments as $f)
                                        <li class="fs-8 text-muted">{{ $f }}</li>
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
                        <div class="card-title">
                            <h4 class="fw-semibold mb-0">SEO</h4>
                        </div>
                    </div>
                    <div class="card-body pt-4 row">
                        <div class="col-lg-6 mb-6">
                            <x-metronic.label for="meta_title" class="col-form-label fw-bold fs-7 text-uppercase text-muted">Meta Title</x-metronic.label>
                            <x-metronic.input id="meta_title" type="text" name="meta_title" :value="old('meta_title', $isEdit ? $event->meta_title : '')" />
                        </div>

                        <div class="col-lg-6 mb-6">
                            <x-metronic.label for="meta_tags" class="col-form-label fw-bold fs-7 text-uppercase text-muted">Meta Tags</x-metronic.label>
                            <x-metronic.input id="meta_tags" type="text" name="meta_tags" :value="old('meta_tags', $isEdit ? $event->meta_tags : '')" />
                        </div>

                        <div class="col-12 mb-6">
                            <x-metronic.label for="meta_description" class="col-form-label fw-bold fs-7 text-uppercase text-muted">Meta Description</x-metronic.label>
                            <textarea id="meta_description" name="meta_description" class="form-control" rows="3">{{ old('meta_description', $isEdit ? $event->meta_description : '') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <div class="col-lg-2">
        <div class="card card-flush shadow-sm">
            <div class="card-header py-5">
                <div class="card-title">
                    <h4 class="fw-semibold mb-0">Publish</h4>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="mb-5">
                    <x-metronic.label for="is_featured" class="col-form-label fw-bold fs-7 text-uppercase text-muted">Featured?</x-metronic.label>
                    <x-metronic.select-option id="is_featured" name="is_featured" data-hide-search="true">
                        @php $feat = old('is_featured', $isEdit ? (int)$event->is_featured : 0); @endphp
                        <option value="0" {{ (string)$feat === '0' ? 'selected' : '' }}>No</option>
                        <option value="1" {{ (string)$feat === '1' ? 'selected' : '' }}>Yes</option>
                    </x-metronic.select-option>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-save me-1"></i>
                    {{ $isEdit ? 'Update Event' : 'Create Event' }}
                </button>
            </div>
        </div>
    </div>
</div>
