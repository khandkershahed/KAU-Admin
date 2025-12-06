@props(['name', 'id' => null, 'label' => null, 'value' => null, 'rows' => 10])

<div class="mb-7">
    @if ($label)
        <x-metronic.label for="{{ $id ?? $name }}" class="col-form-label fw-bold fs-6">
            {{ $label }}
        </x-metronic.label>
    @endif

    <textarea
        id="{{ $id ?? $name }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        {{ $attributes->merge(['class' => 'form-control tinymce-editor']) }}
    >{!! old($name, $value) !!}</textarea>
</div>

@once
    @push('scripts')
        {{-- Self-hosted TinyMCE --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.7.0/tinymce.min.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof tinymce !== 'undefined') {
                    tinymce.init({
                        selector: '.tinymce-editor',

                        // Force TinyMCE to load its assets from cdnjs, not /admin/assets/...
                        base_url: 'https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.7.0',
                        suffix: '.min',

                        height: 550,
                        plugins: 'image link media table lists code fullscreen',
                        toolbar: 'undo redo | styles | bold italic underline | alignleft aligncenter alignright | bullist numlist | table | link image media | code fullscreen',
                        menubar: 'file edit view insert format tools table help',
                        content_style: "body { font-family: -apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,'Helvetica Neue',Arial,sans-serif; font-size:14px; }",

                        images_upload_url: '{{ route('admin.editor.upload') }}',
                        automatic_uploads: true,
                        images_upload_handler: function(blobInfo, success, failure, progress) {
                            let xhr = new XMLHttpRequest();
                            xhr.withCredentials = false;
                            xhr.open('POST', '{{ route('admin.editor.upload') }}');
                            xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');

                            xhr.upload.onprogress = function(e) {
                                if (e.lengthComputable) {
                                    progress(e.loaded / e.total * 100);
                                }
                            };

                            xhr.onload = function() {
                                if (xhr.status < 200 || xhr.status >= 300) {
                                    failure('HTTP Error: ' + xhr.status);
                                    return;
                                }

                                let json;
                                try {
                                    json = JSON.parse(xhr.responseText || '{}');
                                } catch (e) {
                                    failure('Invalid JSON: ' + xhr.responseText);
                                    return;
                                }

                                if (!json || typeof json.location !== 'string') {
                                    failure('Invalid JSON: ' + xhr.responseText);
                                    return;
                                }

                                success(json.location);
                            };

                            xhr.onerror = function() {
                                failure('Image upload failed due to a XHR Transport error.');
                            };

                            let formData = new FormData();
                            formData.append('file', blobInfo.blob(), blobInfo.filename());
                            xhr.send(formData);
                        }
                    });
                }
            });
        </script>
    @endpush
@endonce
