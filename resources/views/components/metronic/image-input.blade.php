{{-- @props(['id' => null, 'name', 'source' => ''])

@php
    $uid = $id ?: $name . '-' . uniqid();
    $preview = $source ?: asset('images/no_image.png');
@endphp

<div class="image-input image-input-outline" data-kt-image-input="true">


    <div class="image-input-wrapper w-125px h-125px"
        style="background-image: url('{{ $preview }}'); background-size: cover;">
    </div>

    <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
           data-kt-image-input-action="change"
           data-bs-toggle="tooltip"
           title="Upload image">

        <i class="fas fa-pencil fs-4"></i>

        <input type="file"
               id="{{ $uid }}"
               name="{{ $name }}"
               accept=".png, .jpg, .jpeg"
               onchange="ktImagePreview(this)" />

        <input type="hidden" name="{{ $name }}_remove" value="0">
    </label>


    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
          data-kt-image-input-action="cancel"
          data-bs-toggle="tooltip"
          title="Cancel image">

        <i class="fas fa-x fs-6 text-danger"></i>
    </span>


    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
          data-kt-image-input-action="remove"
          data-bs-toggle="tooltip"
          title="Remove image"
          onclick="ktRemoveSelectedImage('{{ $uid }}')">

        <i class="fas fa-x fs-6 text-danger"></i>
    </span>

</div>

@error($name)
<div class="text-danger small">{{ $message }}</div>
@enderror


<script>
    function ktImagePreview(input) {
        if (input.files && input.files[0]) {
            let file = input.files[0];

            if (file.size > 2 * 1024 * 1024) {
                Swal.fire("Error", "File must be less than 2MB", "error");
                input.value = "";
                return;
            }

            let reader = new FileReader();
            reader.onload = function(e) {
                let wrapper = input.closest('[data-kt-image-input="true"]')
                                   .querySelector('.image-input-wrapper');
                wrapper.style.backgroundImage = "url('" + e.target.result + "')";
            };
            reader.readAsDataURL(file);

            // New file selected → remove flag = 0
            input.closest('.image-input').querySelector(`input[name="{{ $name }}_remove"]`).value = 0;
        }
    }

    function ktRemoveSelectedImage(uid) {
        let wrapper = document.querySelector(`#${uid}`).closest('[data-kt-image-input="true"]')
                          .querySelector('.image-input-wrapper');

        wrapper.style.backgroundImage = "url('{{ asset('images/no_image.png') }}')";

        // Clear file input
        let input = document.getElementById(uid);
        input.value = "";

        // Set remove flag = 1
        document.querySelector(`input[name="{{ $name }}_remove"]`).value = 1;
    }
</script> --}}

@props(['id' => null, 'name', 'source' => null])

@php
    $uid = $id ?: $name . '-' . uniqid();
    $preview = $source ?: asset('images/no_image.png');
@endphp

<div class="drag-image-input" id="{{ $uid }}-wrapper">

    {{-- DROPZONE AREA --}}
    <div class="drag-drop-zone ms-7" id="{{ $uid }}-dropzone"
        onclick="document.getElementById('{{ $uid }}').click()">

        <div class="preview-area" id="{{ $uid }}-preview" style="background-image: url('{{ $preview }}');">

            <div class="overlay-text">
                <i class="fa-solid fa-cloud-arrow-up fs-1 mb-2"></i>
                <div>Drag & Drop or Click to Upload</div>
            </div>

        </div>
    </div>

    {{-- FILE INPUT --}}
    <input type="file" id="{{ $uid }}" name="{{ $name }}" accept="image/*,.svg" class="d-none"
        onchange="dragImagePreview(event, '{{ $uid }}')">

    <input type="hidden" name="{{ $name }}_remove" id="{{ $uid }}-remove-flag" value="0">

    {{-- ACTION BUTTONS --}}
    <div class="d-flex gap-2 mt-2">

        <button type="button" class="btn btn-light-primary btn-sm"
            onclick="document.getElementById('{{ $uid }}').click()">
            <i class="fa fa-upload me-1"></i> Change
        </button>

        <button type="button" class="btn btn-light-danger btn-sm" onclick="dragImageRemove('{{ $uid }}')">
            <i class="fa fa-trash me-1"></i> Remove
        </button>

    </div>

</div>


{{-- STYLES --}}
<style>
    .drag-drop-zone {
        width: 150px;
        height: 150px;
        border: 2px dashed #cbd5e0;
        border-radius: 12px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.25s;
        background: #fafafa;
    }

    .drag-drop-zone.drag-over {
        border-color: #0a58ca;
        background: #eef5ff;
    }

    .preview-area {
        width: 100%;
        height: 100%;
        border-radius: 10px;
        background-size: cover;
        background-position: center;
        position: relative;
    }

    .overlay-text {
        position: absolute;
        bottom: 0;
        width: 100%;
        background: rgba(0, 0, 0, 0.45);
        color: white;
        text-align: center;
        font-size: 12px;
        padding: 6px 0;
        border-radius: 0 0 10px 10px;
    }
</style>


{{-- SCRIPTS --}}
<script>
    // DRAG & DROP HANDLERS
    document.addEventListener("DOMContentLoaded", function() {
        let dropzone = document.getElementById("{{ $uid }}-dropzone");

        dropzone.addEventListener("dragover", function(e) {
            e.preventDefault();
            dropzone.classList.add("drag-over");
        });

        dropzone.addEventListener("dragleave", function() {
            dropzone.classList.remove("drag-over");
        });

        dropzone.addEventListener("drop", function(e) {
            e.preventDefault();
            dropzone.classList.remove("drag-over");

            let file = e.dataTransfer.files[0];
            let input = document.getElementById("{{ $uid }}");

            input.files = e.dataTransfer.files;

            dragImagePreview({
                target: input
            }, "{{ $uid }}");
        });
    });


    // UNIVERSAL PREVIEW
    function dragImagePreview(event, uid) {
        let file = event.target.files[0];
        if (!file) return;

        if (file.size > 5 * 1024 * 1024) {
            Swal.fire("Error!", "Image must be below 5MB", "error");
            return;
        }

        let url = URL.createObjectURL(file);
        let preview = document.getElementById(uid + "-preview");

        preview.style.backgroundImage = `url('${url}')`;

        document.getElementById(uid + "-remove-flag").value = 0;
    }


    // REMOVE IMAGE
    function dragImageRemove(uid) {
        let preview = document.getElementById(uid + "-preview");

        preview.style.backgroundImage = "url('{{ asset('images/no_image.png') }}')";
        document.getElementById(uid).value = "";
        document.getElementById(uid + "-remove-flag").value = 1;
    }
</script>
