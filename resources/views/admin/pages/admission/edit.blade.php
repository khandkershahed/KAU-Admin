<x-admin-app-layout :title="'Admission Module - Edit'">

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
            <form action="{{ route('admin.admission.update', $admission->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

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
                                    {{ old('parent_id', $admission->parent_id) == $parent->id ? 'selected' : '' }}>
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
                        <x-metronic.input id="title" type="text" name="title" :value="old('title', $admission->title)" required />
                    </div>

                    {{-- Type --}}
                    <div class="col-md-4 mb-7">
                        <x-metronic.label for="type" class="col-form-label required fw-bold fs-6">
                            {{ __('Type') }}
                        </x-metronic.label>
                        <x-metronic.select-option id="type" name="type" data-hide-search="true" required>
                            @php $oldType = old('type', $admission->type); @endphp
                            <option value="menu" {{ $oldType === 'menu' ? 'selected' : '' }}>Menu (no content)
                            </option>
                            <option value="page" {{ $oldType === 'page' ? 'selected' : '' }}>Page (content)</option>
                            <option value="external" {{ $oldType === 'external' ? 'selected' : '' }}>External Link
                            </option>
                        </x-metronic.select-option>
                    </div>

                    {{-- External URL --}}
                    <div class="col-md-8 mb-7 admission-field admission-field-external d-none">
                        <x-metronic.label for="external_url" class="col-form-label fw-bold fs-6">
                            {{ __('External URL') }}
                        </x-metronic.label>
                        <x-metronic.input id="external_url" type="url" name="external_url" :value="old('external_url', $admission->external_url)"
                            placeholder="https://admission.kau.ac.bd/" />
                        <div class="text-muted fs-7">
                            Only used if type is <strong>External Link</strong>.
                        </div>
                    </div>

                    {{-- Banner Image --}}
                    <div class="col-md-4 mb-7 admission-field admission-field-page d-none">
                        <x-metronic.label for="banner_image" class="col-form-label fw-bold fs-6">
                            {{ __('Banner Image') }}
                        </x-metronic.label>
                        <x-metronic.file-input id="banner_image" name="banner_image" :source="isset($admission->banner_image)
                            ? asset('storage/' . $admission->banner_image)
                            : null" />
                    </div>

                    <div class="col-md-3 mb-7">
                        <x-metronic.label for="status" class="col-form-label fw-bold fs-6">
                            {{ __('Status') }}
                        </x-metronic.label>
                        <x-metronic.select-option id="status" name="status" data-hide-search="true">
                            <option value="1" {{ old('status', $admission->status) == 1 ? 'selected' : '' }}>
                                Active</option>
                            <option value="0" {{ old('status', $admission->status) == 0 ? 'selected' : '' }}>
                                Inactive</option>
                        </x-metronic.select-option>
                    </div>
                    {{-- Content --}}
                    <div class="col-lg-12 mb-7 admission-field admission-field-page d-none">
                        <x-metronic.label for="content" class="col-form-label fw-bold fs-6">
                            {{ __('Page Content') }}
                        </x-metronic.label>
                        <x-metronic.editor name="content" label="Page Content" :value="old('content', $admission->content)" rows="12" />
                    </div>

                    {{-- Position --}}
                    {{-- <div class="col-md-3 mb-7">
                        <x-metronic.label for="position" class="col-form-label fw-bold fs-6">
                            {{ __('Position') }}
                        </x-metronic.label>
                        <x-metronic.input id="position" type="number" name="position"
                            :value="old('position', $admission->position)" />
                        <div class="text-muted fs-7">
                            Usually managed by drag &amp; drop sorting.
                        </div>
                    </div> --}}

                    {{-- Status --}}


                    {{-- SEO --}}
                    <div class="col-lg-12 mt-4">
                        <h5 class="fw-bold mb-3">SEO</h5>
                    </div>

                    <div class="col-md-4 mb-7">
                        <x-metronic.label for="meta_title" class="col-form-label fw-bold fs-6">
                            {{ __('Meta Title') }}
                        </x-metronic.label>
                        <x-metronic.input id="meta_title" type="text" name="meta_title" :value="old('meta_title', $admission->meta_title)" />
                    </div>

                    <div class="col-md-4 mb-7">
                        <x-metronic.label for="meta_tags" class="col-form-label fw-bold fs-6">
                            {{ __('Meta Tags') }}
                        </x-metronic.label>
                        <x-metronic.input id="meta_tags" type="text" name="meta_tags" :value="old('meta_tags', $admission->meta_tags)" />
                    </div>

                    <div class="col-md-4 mb-7">
                        <x-metronic.label for="meta_description" class="col-form-label fw-bold fs-6">
                            {{ __('Meta Description') }}
                        </x-metronic.label>
                        <x-metronic.textarea id="meta_description" name="meta_description"
                            placeholder="Short page description">{{ old('meta_description', $admission->meta_description) }}</x-metronic.textarea>
                    </div>
                </div>

                <div class="text-end pt-10">
                    <x-metronic.button type="submit" class="dark rounded-1 px-5">
                        {{ __('Update Admission Item') }}
                    </x-metronic.button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            (function($) {
                function getTypeSelect() {
                    let $el = $('#type');
                    if (!$el.length) {
                        $el = $('select[name="type"]');
                    }
                    return $el.length ? $el : null;
                }

                function toggleAdmissionFields() {
                    const $typeSelect = getTypeSelect();
                    if (!$typeSelect) {
                        // Uncomment to debug:
                        // console.log('Type select not found');
                        return;
                    }

                    const type = ($typeSelect.val() || '').toString();

                    // Hide all conditional fields
                    $('.admission-field').addClass('d-none');

                    if (type === 'page') {
                        $('.admission-field-page').removeClass('d-none');
                    } else if (type === 'external') {
                        $('.admission-field-external').removeClass('d-none');
                    }
                    // type === 'menu' => all stay hidden
                }

                $(document).ready(function() {
                    // Initial state (handles old() + existing $admission->type)
                    toggleAdmissionFields();

                    // Listen to changes on the select (works with Select2 / Metronic)
                    $(document).on('change', '#type, select[name="type"]', function() {
                        toggleAdmissionFields();
                    });

                    // Safety: if Metronic re-inits the select a bit later, re-run
                    setTimeout(toggleAdmissionFields, 300);
                    setTimeout(toggleAdmissionFields, 800);
                });
            })(jQuery);
        </script>
    @endpush



</x-admin-app-layout>
