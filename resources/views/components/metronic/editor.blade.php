@props(['name', 'id' => null, 'label' => null, 'value' => null, 'rows' => 10])

<div class="mb-7">
    @if ($label)
        <x-metronic.label for="{{ $id ?? $name }}" class="col-form-label fw-bold fs-6">
            {{ $label }}
        </x-metronic.label>
    @endif

    <textarea id="{{ $id ?? $name }}" name="{{ $name }}" rows="{{ $rows }}"
        {{ $attributes->merge(['class' => 'form-control tinymce-editor']) }}>{!! old($name, $value) !!}</textarea>
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

                        // ✅ Add text color (forecolor) + highlight (backcolor)
                        toolbar: 'undo redo | styles | bold italic underline | forecolor backcolor | alignleft aligncenter alignright | bullist numlist | table | link image media | code fullscreen',

                        menubar: 'file edit view insert format tools table help',

                        // ✅ Make table insert/grid + row/col tools work reliably
                        table_toolbar: 'tableprops tabledelete | tableinsertrowbefore tableinsertrowafter tabledeleterow | tableinsertcolbefore tableinsertcolafter tabledeletecol',
                        contextmenu: 'table',

                        // ✅ Load your Tailwind compiled CSS into the TinyMCE iframe
                        // Change this path to your actual compiled Tailwind CSS file:
                        content_css: [
                            '{{ asset('css/app.css') }}'
                        ],

                        // ✅ Use Tailwind classes on the editor body (works only if Tailwind is loaded via content_css)
                        body_class: 'font-sans text-sm',

                        // ✅ Prevent TinyMCE from injecting inline table styles/attributes by default
                        table_default_attributes: {},
                        table_default_styles: {},

                        images_upload_url: '{{ route('admin.editor.upload') }}',
                        automatic_uploads: true,

                        // TinyMCE 6: MUST return a Promise
                        images_upload_handler: function(blobInfo, progress) {
                            return new Promise(function(resolve, reject) {
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
                                        reject('HTTP Error: ' + xhr.status);
                                        return;
                                    }

                                    let json;
                                    try {
                                        json = JSON.parse(xhr.responseText || '{}');
                                    } catch (e) {
                                        reject('Invalid JSON: ' + xhr.responseText);
                                        return;
                                    }

                                    if (!json || typeof json.location !== 'string') {
                                        reject('Invalid JSON: ' + xhr.responseText);
                                        return;
                                    }

                                    resolve(json.location);
                                };

                                xhr.onerror = function() {
                                    reject('Image upload failed due to a XHR Transport error.');
                                };

                                let formData = new FormData();
                                formData.append('file', blobInfo.blob(), blobInfo.filename());
                                xhr.send(formData);
                            });
                        }
                    });
                }
            });
        </script>
    @endpush
@endonce
