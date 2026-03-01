<x-admin-app-layout :title="'Site Files'">

    <div class="card card-flash">
        <div class="card-header mt-6">
            <div class="card-title">
                <h3 class="mb-0">Private Site Files (Docs / PDFs / Images)</h3>
            </div>
            <div class="card-toolbar">
                <form action="{{ route('admin.site-files.store') }}" method="POST" enctype="multipart/form-data" class="d-flex align-items-center gap-3">
                    @csrf
                    <input type="file" name="file" class="form-control form-control-sm" required>
                    <button type="submit" class="btn btn-light-primary btn-sm">Upload</button>
                </form>
            </div>
        </div>

        <div class="card-body pt-0">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-striped table-row-bordered gy-5 gs-7 border rounded">
                    <thead class="bg-dark text-light">
                        <tr>
                            <th width="5%">#</th>
                            <th width="25%">File</th>
                            <th width="12%">Type</th>
                            <th width="10%">Size</th>
                            <th width="38%">Frontend URL (kau.ac.bd)</th>
                            <th width="10%">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="fw-bold text-gray-600">
                        @forelse ($files as $i => $f)
                            @php
                                $frontend = rtrim(config('app.frontend_url', config('app.url')), '/');
                                $filename = $f->filename();
                                $frontendUrl = $frontend . '/files/' . rawurlencode($filename);
                                $sizeKb = $f->size ? number_format($f->size / 1024, 2) . ' KB' : '0 KB';
                            @endphp
                            <tr>
                                <td>{{ $files->firstItem() + $i }}</td>
                                <td class="text-start">
                                    <div class="d-flex flex-column">
                                        <span class="text-gray-900">{{ $f->original_name }}</span>
                                        <small class="text-muted">Stored: {{ $filename }}</small>
                                    </div>
                                </td>
                                <td class="text-start">{{ $f->mime ?? '-' }}</td>
                                <td class="text-start">{{ $sizeKb }}</td>
                                <td class="text-start">
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="text" class="form-control form-control-sm" value="{{ $frontendUrl }}" readonly>
                                        <button type="button" class="btn btn-sm btn-light" onclick="navigator.clipboard.writeText('{{ $frontendUrl }}')">Copy</button>
                                    </div>
                                </td>
                                <td class="text-start">
                                    <form action="{{ route('admin.site-files.destroy', $f) }}" method="POST" onsubmit="return confirm('Delete this file?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-10">No files uploaded yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $files->links() }}
            </div>
        </div>
    </div>

</x-admin-app-layout>
