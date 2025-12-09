{{-- @props(['id' => '', 'name', 'source' => ''])

<div class="row gx-1">
    <div class="col-10">
        <input id="{{ $id ?? 'file-input' }}" type="file" class="form-control @error($name)is-invalid @enderror"
            name="{{ $name }}" accept="image/*" {{ $attributes }} onchange="previewFile(this)" />

        @error($name)
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="col-2 mt-n7">
        <img id="{{ $id ?? 'file-input' }}-preview" src="{{ !empty($source) ? $source : asset('images/no_image.png') }}"
            alt="Image Preview" class="img-thumbnail" style="display: {{ !empty($source) ? 'block' : 'none' }};height: 65px;">
    </div>
</div>

<script>

    function previewFile(input) {
        var file = input.files[0];
        var preview = document.getElementById(input.id + '-preview');
        var reader = new FileReader();

        if (file && file.size > 2 * 1024 * 1024) { // 2MB
            alert("File size must be less than 2MB");
            input.value = ''; // Clear the input
            preview.src = '';
            preview.style.display = 'none';
            return;
        }

        if (file) {
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        } else {
            preview.src = '';
            preview.style.display = 'none';
        }
    }
</script> --}}


{{--
@props(['id', 'name', 'source' => ''])
@php
   $uid = $id ?? $name . '-' . uniqid();
@endphp
<div class="row gx-1">
    <div class="col-10">
        <input id="{{ $uid }}" type="file"
            class="form-control form-control-solid @error($name)is-invalid @enderror" name="{{ $name }}"
            accept="image/*" {{ $attributes }} onchange="previewFile(this)" />

        @error($name)
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="col-2 mt-n7">
        <img id="{{ $id ?? 'file-input' }}-preview" src="{{ !empty($source) ? $source : asset('images/no_image.png') }}"
            alt="Image Preview" class="img-thumbnail file-preview"
            style="display: {{ !empty($source) ? 'block' : 'none' }};" width="55px" height="68px">
    </div>
</div>

<script>
    function previewFile(input) {
        var file = input.files[0];
        var preview = input.closest('.row').querySelector(
        '.file-preview'); // Using closest to find the parent .row and querySelector for .file-preview
        var reader = new FileReader();

        if (file && file.size > 2 * 1024 * 1024) { // 2MB
            alert("File size must be less than 2MB");
            input.value = ''; // Clear the input
            preview.src = '';
            preview.style.display = 'none';
            return;
        }

        if (file) {
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        } else {
            preview.src = '';
            preview.style.display = 'none';
        }
    }
</script> --}}


@props(['id', 'name', 'source' => ''])

@php
    $uid = $id ?? $name . '-' . uniqid();
@endphp

<div class="row gx-1 align-items-start image-input-wrapper">
    <div class="col-10">
        <input id="{{ $uid }}" type="file"
            class="form-control form-control-solid @error($name)is-invalid @enderror" name="{{ $name }}"
            accept="image/*" {{ $attributes }} onchange="previewFile(this)" />

        @error($name)
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-2 position-relative mt-n7">
        <img id="{{ $uid }}-preview" src="{{ $source ?: asset('images/no_image.png') }}" alt="Preview"
            class="img-thumbnail file-preview"
            style="display: {{ $source ? 'block' : 'none' }}; width: 55px; height: 68px;">

        {{-- REMOVE BUTTON --}}
        <button type="button" class="btn btn-sm btn-light-danger remove-image-btn"
            style="
                    position:absolute;
                    top:-6px;
                    right:-6px;
                    padding:0px 6px;
                    border-radius: 50%;
                    font-size: 11px;
                "
            onclick="removeSelectedImage('{{ $uid }}')">
            ✕
        </button>
    </div>
</div>

<script>
    function previewFile(input) {
        let file = input.files[0];
        let wrapper = input.closest('.image-input-wrapper');
        let preview = wrapper.querySelector('.file-preview');
        let reader = new FileReader();

        if (file && file.size > 2 * 1024 * 1024) {
            alert("File size must be less than 2MB");
            input.value = '';
            preview.src = '';
            preview.style.display = 'none';
            return;
        }

        if (file) {
            reader.onload = e => {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            preview.src = '';
            preview.style.display = 'none';
        }
    }

    function removeSelectedImage(uid) {
        let input = document.getElementById(uid);
        let preview = document.getElementById(uid + '-preview');

        // Clear file input value
        input.value = "";

        // Hide preview
        preview.src = "";
        preview.style.display = "none";

        // Optional hidden field to inform backend to remove image:
        // (Creating only if needed)
        let existing = document.getElementById(uid + '-remove-flag');

        if (!existing) {
            let hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = input.name + '_remove';
            hidden.value = '1';
            hidden.id = uid + '-remove-flag';
            input.parentNode.appendChild(hidden);
        } else {
            existing.value = "1";
        }
    }
</script>
