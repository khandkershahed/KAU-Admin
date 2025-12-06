<div class="table-responsive">
    <table class="table table-row-bordered align-middle">
        <thead class="fw-bold" style="background: #f5f8fa;">
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Category</th>
                <th>Publish Date</th>
                <th>Featured</th>
                <th>Status</th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($notices as $notice)
                <tr>
                    <td>{{ $loop->iteration + ($notices->firstItem() - 1) }}</td>
                    <td>{{ $notice->title }}</td>
                    <td>{{ optional($notice->category)->name ?? '-' }}</td>
                    <td>{{ $notice->publish_date?->format('Y-m-d') ?? '-' }}</td>
                    <td>
                        @if ($notice->is_featured)
                            <span class="badge badge-light-warning">Yes</span>
                        @else
                            <span class="badge badge-light">No</span>
                        @endif
                    </td>
                    <td>
                        <span
                            class="badge badge-light-{{ $notice->status === 'published' ? 'success' : ($notice->status === 'draft' ? 'secondary' : 'danger') }}">
                            {{ ucfirst($notice->status) }}
                        </span>
                    </td>
                    <td class="text-end">
                        @can('edit notice')
                            <a href="{{ route('admin.notice.edit', $notice->id) }}"
                                class="btn btn-icon btn-light-success btn-sm me-1">
                                <i class="fa-solid fa-pen-to-square fs-6"></i>
                            </a>
                        @endcan

                        @can('delete notice')
                            <button type="button" class="btn btn-icon btn-light-danger btn-sm deleteNoticeBtn"
                                data-id="{{ $notice->id }}" data-url="{{ route('admin.notice.destroy', $notice->id) }}">
                                <i class="fa-solid fa-trash fs-6"></i>
                            </button>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">No notices found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if ($notices->hasPages())
    <div class="mt-3">
        {{ $notices->appends(['notice_search' => request('notice_search')])->links() }}
    </div>
@endif
