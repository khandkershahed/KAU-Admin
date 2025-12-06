<x-admin-app-layout :title="'Edit Notice'">

    <div class="card card-flash">
        <div class="card-header mt-6">
            <div class="card-toolbar">
                <a href="{{ route('admin.notice.index') }}" class="btn btn-light-info">
                    <span class="svg-icon svg-icon-3"><i class="fas fa-arrow-left"></i></span>
                    Back to the list
                </a>
            </div>
        </div>

        <div class="card-body pt-0">
            <form method="POST" action="{{ route('admin.notice.update', $notice->id) }}"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-lg-4 mb-7">
                        <x-metronic.label for="category_id" class="col-form-label fw-bold fs-6">
                            Category
                        </x-metronic.label>
                        <x-metronic.select-option id="category_id" name="category_id"
                            data-hide-search="false" data-placeholder="Select category">
                            <option value="">-- None --</option>
                            @foreach ($categories as $c)
                                <option value="{{ $c->id }}"
                                    {{ old('category_id', $notice->category_id) == $c->id ? 'selected' : '' }}>
                                    {{ $c->name }}
                                </option>
                            @endforeach
                        </x-metronic.select-option>
                    </div>

                    <div class="col-lg-5 mb-7">
                        <x-metronic.label for="title" class="col-form-label required fw-bold fs-6">
                            Title
                        </x-metronic.label>
                        <x-metronic.input id="title" type="text" name="title"
                            :value="old('title', $notice->title)" required />
                    </div>

                    <div class="col-lg-3 mb-7">
                        <x-metronic.label for="status" class="col-form-label fw-bold fs-6">
                            Status
                        </x-metronic.label>
                        <x-metronic.select-option id="status" name="status" data-hide-search="true">
                            <option value="draft"
                                {{ old('status', $notice->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published"
                                {{ old('status', $notice->status) === 'published' ? 'selected' : '' }}>Published
                            </option>
                            <option value="archived"
                                {{ old('status', $notice->status) === 'archived' ? 'selected' : '' }}>Archived
                            </option>
                        </x-metronic.select-option>
                    </div>

                    <div class="col-lg-3 mb-7">
                        <x-metronic.label for="publish_date" class="col-form-label fw-bold fs-6">
                            Publish Date
                        </x-metronic.label>
                        <x-metronic.input id="publish_date" type="date" name="publish_date"
                            :value="old('publish_date', optional($notice->publish_date)->format('Y-m-d'))" />
                    </div>

                    <div class="col-lg-3 mb-7">
                        <x-metronic.label for="is_featured" class="col-form-label fw-bold fs-6">
                            Featured?
                        </x-metronic.label>
                        <x-metronic.select-option id="is_featured" name="is_featured" data-hide-search="true">
                            <option value="0"
                                {{ old('is_featured', $notice->is_featured) == '0' ? 'selected' : '' }}>No
                            </option>
                            <option value="1"
                                {{ old('is_featured', $notice->is_featured) == '1' ? 'selected' : '' }}>Yes
                            </option>
                        </x-metronic.select-option>
                    </div>

                    {{-- Body --}}
                    <div class="col-lg-12 mb-7">
                        <x-metronic.editor name="body" label="Body"
                            :value="old('body', $notice->body)" rows="15" />
                    </div>

                    {{-- Attachments --}}
                    <div class="col-lg-12 mb-7">
                        <x-metronic.label for="attachments" class="col-form-label fw-bold fs-6">
                            Attachments (Upload to replace existing)
                        </x-metronic.label>
                        <input type="file" id="attachments" name="attachments[]" class="form-control" multiple />
                        <div class="text-muted fs-7 mt-2">
                            Existing files:
                        </div>
                        @if (is_array($notice->attachments) && count($notice->attachments))
                            <ul class="mt-1">
                                @foreach ($notice->attachments as $file)
                                    <li class="text-muted">{{ $file }}</li>
                                @endforeach
                            </ul>
                        @else
                            <div class="text-muted fs-7">No attachments.</div>
                        @endif
                    </div>

                    {{-- SEO --}}
                    <div class="col-lg-4 mb-7">
                        <x-metronic.label for="meta_title" class="col-form-label fw-bold fs-6">
                            Meta Title
                        </x-metronic.label>
                        <x-metronic.input id="meta_title" type="text" name="meta_title"
                            :value="old('meta_title', $notice->meta_title)" />
                    </div>

                    <div class="col-lg-4 mb-7">
                        <x-metronic.label for="meta_tags" class="col-form-label fw-bold fs-6">
                            Meta Tags
                        </x-metronic.label>
                        <x-metronic.input id="meta_tags" type="text" name="meta_tags"
                            :value="old('meta_tags', $notice->meta_tags)" />
                    </div>

                    <div class="col-lg-4 mb-7">
                        <x-metronic.label for="meta_description" class="col-form-label fw-bold fs-6">
                            Meta Description
                        </x-metronic.label>
                        <x-metronic.textarea id="meta_description" name="meta_description"
                            placeholder="Short description">{{ old('meta_description', $notice->meta_description) }}</x-metronic.textarea>
                    </div>
                </div>

                <div class="pt-15 text-end">
                    <x-metronic.button type="submit" class="dark rounded-1 px-5">
                        Update Notice
                    </x-metronic.button>
                </div>
            </form>
        </div>
    </div>

</x-admin-app-layout>
