<x-admin-app-layout :title="'Galleries'">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title fw-bold">Galleries</h3>
            @can('create gallery')
                <a href="{{ route('admin.galleries.create') }}" class="btn btn-sm btn-primary">
                    <i class="fa fa-plus me-2"></i>Add Gallery
                </a>
            @endcan
        </div>

        <div class="card-body">
            <table class="table table-row-dashed align-middle">
                <thead>
                    <tr class="fw-bold text-muted">
                        <th>Title</th>
                        <th>Owner</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($galleries as $gallery)
                        <tr>
                            <td class="fw-semibold">{{ $gallery->title }}</td>
                            <td>
                                <span class="badge badge-light-info">
                                    {{ ucfirst($gallery->owner_type) }}
                                </span>
                            </td>
                            <td>{{ ucfirst($gallery->type) }}</td>
                            <td>
                                @if($gallery->is_active)
                                    <span class="badge badge-light-success">Active</span>
                                @else
                                    <span class="badge badge-light-danger">Inactive</span>
                                @endif
                            </td>
                            <td class="text-end">
                                @can('edit gallery')
                                    <a href="{{ route('admin.galleries.edit', $gallery->id) }}" class="btn btn-sm btn-light me-1">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                @endcan
                                @can('delete gallery')
                                    <a href="{{ route('admin.galleries.destroy', $gallery->id) }}"
                                       class="btn btn-sm btn-light delete">
                                        <i class="fa fa-trash text-danger"></i>
                                    </a>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">No galleries found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-admin-app-layout>
