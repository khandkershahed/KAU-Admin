<x-admin-app-layout :title="'Admission Module - Create'">

    <div class="card card-flash">
        <div class="card-header mt-6">
            <div class="card-toolbar">
                <a href="{{ route('admin.admission.index') }}" class="btn btn-light-info">
                    <span class="svg-icon svg-icon-3"><i class="fas fa-arrow-left"></i></span>
                    Back to the list
                </a>
            </div>
        </div>

        <div class="card-body pt-0">
            <form action="{{ route('admin.admission.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    {{-- Parent --}}
                    <div class="col-md-4 mb-7">
                        <x-metronic.label for="parent_id" class="col-form-label fw-bold fs-6">
                            {{ __('Parent (optional)') }}
                        </x-metronic.label>
                        <x-metronic.select-option id="parent_id" name="parent_id" data-hide-search="false"
                            data-placeholder="-- Root --">
                            <option value="">-- Root --</option>
                            @foreach ($parents as $parent)
                                <option value="{{ $parent->id }}"
                                    {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                    {{ $parent->title }}
                                </option>
                            @endforeach
                        </x-metronic.select-option>
                    </div>

                    {{-- Title --}}
                    <div class="col-md-4 mb-7">
                        <x-metronic.label for="title" class="col-form-label required fw-bold fs-6">
                            {{ __('Title') }}
                        </x-metronic.label>
                        <x-metronic.input id="title" type="text" name="title" :value="old('title')"
                            placeholder="e.g. Undergraduate Programs" required />
                    </div>

                    {{-- Type --}}
                    <div class="col-md-4 mb-7">
                        <x-metronic.label for="type" class="col-form-label required fw-bold fs-6">
                            {{ __('Type') }}
                        </x-metronic.label>
                        <x-metronic.select-option id="type" name="type" data-hide-search="true" required>
                            @php $oldType = old('type', 'menu'); @endphp
                            <option value="menu" {{ $oldType === 'menu' ? 'selected' : '' }}>Menu (no content)
                            </option>
                            <option value="page" {{ $oldType === 'page' ? 'selected' : '' }}>Page (content)</option>
                            <option value="external" {{ $oldType === 'external' ? 'selected' : '' }}>External Link
                            </option>
                        </x-metronic.select-option>
                    </div>

                    {{-- External URL (for external type) --}}
                    <div class="col-md-8 mb-7 admission-field admission-field-external d-none">
                        <x-metronic.label for="external_url" class="col-form-label fw-bold fs-6">
                            {{ __('External URL') }}
                        </x-metronic.label>
                        <x-metronic.input id="external_url" type="url" name="external_url" :value="old('external_url')"
                            placeholder="https://admission.kau.ac.bd/" />
                        <div class="text-muted fs-7">
                            Only used if type is <strong>External Link</strong>.
                        </div>
                    </div>

                    {{-- Banner Image (for page type) --}}
                    <div class="col-md-6 mb-7 admission-field admission-field-page d-none">
                        <x-metronic.label for="banner_image" class="col-form-label fw-bold fs-6">
                            {{ __('Banner Image') }}
                        </x-metronic.label>
                        <x-metronic.file-input id="banner_image" name="banner_image" />
                        <div class="text-muted fs-7">
                            Optional banner image for page header.
                        </div>
                    </div>

                    <div class="col-md-3 mb-7">
                        <x-metronic.label for="status" class="col-form-label fw-bold fs-6">
                            {{ __('Status') }}
                        </x-metronic.label>
                        <x-metronic.select-option id="status" name="status" data-hide-search="true">
                            <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('status', 1) == 0 ? 'selected' : '' }}>Inactive</option>
                        </x-metronic.select-option>
                    </div>
                    {{-- Content (for page type) --}}
                    <div class="col-lg-12 mb-7 admission-field admission-field-page d-none">
                        <x-metronic.label for="content" class="col-form-label fw-bold fs-6">
                            {{ __('Page Content') }}
                        </x-metronic.label>
                        <x-metronic.editor name="content" label="Page Content" :value="old('content')" rows="12" />
                        <div class="text-muted fs-7">
                            Detailed admission information shown on the frontend.
                        </div>
                    </div>

                    {{-- Position --}}
                    {{-- <div class="col-md-3 mb-7">
                        <x-metronic.label for="position" class="col-form-label fw-bold fs-6">
                            {{ __('Position') }}
                        </x-metronic.label>
                        <x-metronic.input id="position" type="number" name="position" :value="old('position', 0)" />
                        <div class="text-muted fs-7">
                            Usually managed by drag &amp; drop sorting. 0 = auto.
                        </div>
                    </div> --}}

                    {{-- Status --}}


                    {{-- SEO fields --}}
                    <div class="col-lg-12 mt-4">
                        <h5 class="fw-bold mb-3">SEO</h5>
                    </div>

                    <div class="col-md-4 mb-7">
                        <x-metronic.label for="meta_title" class="col-form-label fw-bold fs-6">
                            {{ __('Meta Title') }}
                        </x-metronic.label>
                        <x-metronic.input id="meta_title" type="text" name="meta_title" :value="old('meta_title')" />
                    </div>

                    <div class="col-md-4 mb-7">
                        <x-metronic.label for="meta_tags" class="col-form-label fw-bold fs-6">
                            {{ __('Meta Tags') }}
                        </x-metronic.label>
                        <x-metronic.input id="meta_tags" type="text" name="meta_tags" :value="old('meta_tags')"
                            placeholder="comma,separated,tags" />
                    </div>

                    <div class="col-md-4 mb-7">
                        <x-metronic.label for="meta_description" class="col-form-label fw-bold fs-6">
                            {{ __('Meta Description') }}
                        </x-metronic.label>
                        <x-metronic.textarea id="meta_description" name="meta_description"
                            placeholder="Short page description">{{ old('meta_description') }}</x-metronic.textarea>
                    </div>
                </div>

                <div class="text-end pt-10">
                    <x-metronic.button type="submit" class="dark rounded-1 px-5">
                        {{ __('Create Admission Item') }}
                    </x-metronic.button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            (function($) {
                function toggleAdmissionFields() {
                    // Try to get the select by id or by name (covers x-metronic select)
                    var $typeSelect = $('#type');
                    if (!$typeSelect.length) {
                        $typeSelect = $('select[name="type"]');
                    }

                    var type = ($typeSelect.val() || '').toString();

                    // Hide all conditional sections
                    $('.admission-field').addClass('d-none');

                    if (type === 'page') {
                        $('.admission-field-page').removeClass('d-none');
                    } else if (type === 'external') {
                        $('.admission-field-external').removeClass('d-none');
                    }
                    // if type === 'menu' → everything stays hidden (default)
                }

                $(document).ready(function() {
                    // Initial state (handles old() after validation error)
                    toggleAdmissionFields();

                    // Listen to changes on the select (also works with select2/metronic)
                    $(document).on('change', '#type, select[name="type"]', function() {
                        toggleAdmissionFields();
                    });
                });
            })(jQuery);
        </script>
    @endpush

</x-admin-app-layout>
