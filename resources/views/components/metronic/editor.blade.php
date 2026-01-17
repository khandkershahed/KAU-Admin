@props(['name', 'id' => null, 'label' => null, 'value' => null, 'rows' => 10])

@php
    $editorId = $id ?? $name;
@endphp

<div class="mb-7">
    @if ($label)
        <x-metronic.label for="{{ $editorId }}" class="col-form-label fw-bold fs-6">
            {{ $label }} (<span class="text-danger">Don't Copy from tailwind based styles</span>)
        </x-metronic.label>
    @endif

    <textarea id="{{ $editorId }}" name="{{ $name }}" rows="{{ $rows }}" data-tinymce="1"
        {{ $attributes->merge(['class' => 'form-control tinymce-editor']) }}>{!! old($name, $value) !!}</textarea>
</div>

@once
    @push('styles')
        <style>

            .tox-tinymce-aux,
            .tox .tox-menu,
            .tox .tox-pop,
            .tox .tox-dialog,
            .tox .tox-dialog-wrap {
                z-index: 20000 !important;
            }

            /* Fix Insert Table grid stuck at 0x0 / clicks not detected */
            .tox .tox-insert-table-grid,
            .tox .tox-insert-table-grid * {
                pointer-events: auto !important;
            }


            /* Neutralize GLOBAL table/td rules ONLY inside the TinyMCE insert grid */
            .tox .tox-insert-table-grid__table,
            .tox .tox-insert-table-grid__table * {
                all: unset;
            }

            /* Re-apply only the necessary layout so TinyMCE can measure hover */
            .tox .tox-insert-table-grid__table {
                display: table !important;
                border-collapse: separate !important;
                border-spacing: 0 !important;
                table-layout: fixed !important;
            }

            .tox .tox-insert-table-grid__row {
                display: table-row !important;
            }

            .tox .tox-insert-table-grid__cell {
                display: table-cell !important;
                box-sizing: border-box !important;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.7.0/tinymce.min.js"></script>

        <script>
            (function() {
                function initTinyMceFor(selectorRoot) {
                    if (typeof tinymce === 'undefined') return;

                    var root = selectorRoot || document;
                    var textareas = root.querySelectorAll('textarea.tinymce-editor[data-tinymce="1"]');

                    textareas.forEach(function(el) {
                        var id = el.getAttribute('id');
                        if (!id) return;

                        // Prevent double init
                        if (tinymce.get(id)) return;

                        // tinymce.init({
                        //     selector: '#' + CSS.escape(id),

                        //     // Force TinyMCE to load its assets from cdnjs, not /admin/assets/...
                        //     base_url: 'https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.7.0',
                        //     suffix: '.min',

                        //     height: 550,

                        //     plugins: 'image link media table lists code fullscreen',

                        //     // ✅ Includes text color and highlight
                        //     toolbar: 'undo redo | styles | bold italic underline | forecolor backcolor | alignleft aligncenter alignright | bullist numlist | table | link image media | code fullscreen',

                        //     menubar: 'file edit view insert format tools table help',

                        //     // ✅ Table features (row/column etc.)
                        //     table_toolbar: 'tableprops tabledelete | tableinsertrowbefore tableinsertrowafter tabledeleterow | tableinsertcolbefore tableinsertcolafter tabledeletecol',
                        //     contextmenu: 'link image table',

                        //     // ✅ Helps inside modals / overflow containers
                        //     fixed_toolbar_container: 'body',

                        //     content_style: "body { font-family: -apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,'Helvetica Neue',Arial,sans-serif; font-size:14px; }",

                        //     images_upload_url: '{{ route('admin.editor.upload') }}',
                        //     automatic_uploads: true,

                        //     // TinyMCE 6: MUST return a Promise
                        //     images_upload_handler: function(blobInfo, progress) {
                        //         return new Promise(function(resolve, reject) {
                        //             var xhr = new XMLHttpRequest();
                        //             xhr.withCredentials = false;
                        //             xhr.open('POST', '{{ route('admin.editor.upload') }}');
                        //             xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');

                        //             xhr.upload.onprogress = function(e) {
                        //                 if (e.lengthComputable) {
                        //                     progress(e.loaded / e.total * 100);
                        //                 }
                        //             };

                        //             xhr.onload = function() {
                        //                 if (xhr.status < 200 || xhr.status >= 300) {
                        //                     reject('HTTP Error: ' + xhr.status);
                        //                     return;
                        //                 }

                        //                 var json;
                        //                 try {
                        //                     json = JSON.parse(xhr.responseText || '{}');
                        //                 } catch (e) {
                        //                     reject('Invalid JSON: ' + xhr.responseText);
                        //                     return;
                        //                 }

                        //                 if (!json || typeof json.location !== 'string') {
                        //                     reject('Invalid JSON: ' + xhr.responseText);
                        //                     return;
                        //                 }

                        //                 resolve(json.location);
                        //             };

                        //             xhr.onerror = function() {
                        //                 reject(
                        //                     'Image upload failed due to a XHR Transport error.'
                        //                     );
                        //             };

                        //             var formData = new FormData();
                        //             formData.append('file', blobInfo.blob(), blobInfo
                        //                 .filename());
                        //             xhr.send(formData);
                        //         });
                        //     }
                        // });
                        tinymce.init({
                            selector: '#' + CSS.escape(id),

                            base_url: 'https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.7.0',
                            suffix: '.min',

                            height: 550,

                            plugins: 'image link media table lists code fullscreen paste',

                            toolbar: 'undo redo | styles | bold italic underline | forecolor backcolor | alignleft aligncenter alignright | bullist numlist | table | link image media | code fullscreen',

                            menubar: 'file edit view insert format tools table help',

                            table_toolbar: 'tableprops tabledelete | tableinsertrowbefore tableinsertrowafter tabledeleterow | tableinsertcolbefore tableinsertcolafter tabledeletecol',
                            contextmenu: 'link image table',

                            fixed_toolbar_container: 'body',

                            content_style: "body { font-family: -apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,'Helvetica Neue',Arial,sans-serif; font-size:14px; }",

                            /* 🔴 CRITICAL FIXES */
                            relative_urls: false,
                            remove_script_host: false,
                            convert_urls: false,
                            document_base_url: '{{ url('/') }}/',

                            paste_data_images: true,
                            automatic_uploads: true,
                            images_reuse_filename: false,

                            images_upload_url: '{{ route('admin.editor.upload') }}',

                            images_upload_handler: function(blobInfo, progress) {
                                return new Promise(function(resolve, reject) {
                                    var xhr = new XMLHttpRequest();
                                    xhr.open('POST', '{{ route('admin.editor.upload') }}');
                                    xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');

                                    xhr.onload = function() {
                                        if (xhr.status !== 200) {
                                            reject('HTTP Error: ' + xhr.status);
                                            return;
                                        }

                                        var json = JSON.parse(xhr.responseText || '{}');

                                        if (!json.location) {
                                            reject('Invalid JSON: ' + xhr.responseText);
                                            return;
                                        }

                                        resolve(json.location);
                                    };

                                    xhr.onerror = function() {
                                        reject('Upload failed');
                                    };

                                    var formData = new FormData();
                                    formData.append('file', blobInfo.blob(), blobInfo.filename());
                                    xhr.send(formData);
                                });
                            }
                        });

                    });
                }

                // Init on page load
                document.addEventListener('DOMContentLoaded', function() {
                    initTinyMceFor(document);
                });

                // Init when Bootstrap/Metronic modal opens (content becomes interactive)
                document.addEventListener('shown.bs.modal', function(e) {
                    initTinyMceFor(e.target);
                });

                // If content is injected later (AJAX, Livewire, etc.), auto-init new editors
                // (This is safe because we check tinymce.get(id) before init)
                var observer = new MutationObserver(function(mutations) {
                    mutations.forEach(function(m) {
                        if (!m.addedNodes || !m.addedNodes.length) return;

                        m.addedNodes.forEach(function(node) {
                            if (!(node instanceof HTMLElement)) return;

                            if (node.matches && node.matches(
                                    'textarea.tinymce-editor[data-tinymce="1"]')) {
                                initTinyMceFor(document);
                                return;
                            }

                            if (node.querySelector && node.querySelector(
                                    'textarea.tinymce-editor[data-tinymce="1"]')) {
                                initTinyMceFor(node);
                                return;
                            }
                        });
                    });
                });

                document.addEventListener('DOMContentLoaded', function() {
                    observer.observe(document.body, {
                        childList: true,
                        subtree: true
                    });
                });
            })();
        </script>
    @endpush
@endonce
