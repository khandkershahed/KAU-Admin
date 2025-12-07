<div class="js-section-form" data-section-key="vc_message" style="display:none;">
    <h4 class="fw-bold mb-4">Message from Vice Chancellor</h4>

    <div class="mb-3">
        <x-metronic.label class="col-form-label fw-bold fs-7">
            Message Title
        </x-metronic.label>
        <input type="text" name="message_title" class="form-control form-control-sm"
            value="{{ old('message_title', $vc->message_title) }}">
    </div>

    <div class="row g-3">
        <div class="col-md-7">
            <div class="mb-3">
                <x-metronic.label class="col-form-label fw-bold fs-7">
                    Message Text
                </x-metronic.label>
                <textarea name="message_text" rows="4" class="form-control form-control-sm">{{ old('message_text', $vc->message_text) }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <x-metronic.label class="col-form-label fw-bold fs-7">
                        VC Name
                    </x-metronic.label>
                    <input type="text" name="vc_name" class="form-control form-control-sm"
                        value="{{ old('vc_name', $vc->vc_name) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <x-metronic.label class="col-form-label fw-bold fs-7">
                        VC Designation
                    </x-metronic.label>
                    <input type="text" name="vc_designation" class="form-control form-control-sm"
                        value="{{ old('vc_designation', $vc->vc_designation) }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <x-metronic.label class="col-form-label fw-bold fs-7">
                        Button Name
                    </x-metronic.label>
                    <input type="text" name="vc_button_name" class="form-control form-control-sm"
                        value="{{ old('vc_button_name', $vc->button_name) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <x-metronic.label class="col-form-label fw-bold fs-7">
                        Button URL
                    </x-metronic.label>
                    <input type="text" name="vc_button_url" class="form-control form-control-sm"
                        value="{{ old('vc_button_url', $vc->button_url) }}">
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <x-metronic.label class="col-form-label fw-bold fs-7">
                VC Photo
            </x-metronic.label>
            <div class="image-input image-input-outline" data-kt-image-input="true">
                @php
                    $vcImg = $vc->vc_image
                        ? asset('storage/' . $vc->vc_image)
                        : asset('media/placeholder/blank-image.svg');
                @endphp
                <div class="image-input-wrapper w-150px h-150px" style="background-image: url('{{ $vcImg }}')">
                </div>

                <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                    data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change photo">
                    <i class="bi bi-pencil-fill fs-7"></i>
                    <input type="file" name="vc_image" accept=".png,.jpg,.jpeg" />
                    <input type="hidden" name="vc_image_remove" value="0" />
                </label>

                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                    data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove photo"
                    onclick="document.querySelector('input[name=vc_image_remove]').value=1;">
                    <i class="bi bi-x fs-2"></i>
                </span>
            </div>
        </div>
    </div>
</div>
