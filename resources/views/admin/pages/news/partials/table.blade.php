<div class="table-responsive">
    <table class="table table-row-bordered align-middle">
        <thead class="fw-bold" style="background:#f5f8fa;">
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Category</th>
                <th>Author</th>
                <th>Published At</th>
                <th>Featured</th>
                <th>Status</th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($news as $item)
                <tr>
                    <td>{{ $loop->iteration + ($news->firstItem() - 1) }}</td>
                    <td>{{ $item->title }}</td>
                    <td>{{ $item->category ?? '-' }}</td>
                    <td>{{ $item->author ?? '-' }}</td>
                    <td>{{ $item->published_at?->format('Y-m-d') ?? '-' }}</td>
                    <td>
                        @if ($item->is_featured)
                            <span class="badge badge-light-warning">Yes</span>
                        @else
                            <span class="badge badge-light">No</span>
                        @endif
                    </td>
                    <td>
                        <span
                            class="badge badge-light-{{ $item->status === 'published' ? 'success' : ($item->status === 'draft' ? 'secondary' : 'danger') }}">
                            {{ ucfirst($item->status) }}
                        </span>
                    </td>
                    <td class="text-end">
                        @can('edit news')
                            <a href="{{ route('admin.news.edit', $item->id) }}"
                                class="btn btn-icon btn-light-success btn-sm me-1">
                                <i class="fa-solid fa-pen-to-square fs-6"></i>
                            </a>
                        @endcan

                        @can('delete news')
                            <button type="button" class="btn btn-icon btn-light-danger btn-sm deleteNewsBtn"
                                data-url="{{ route('admin.news.destroy', $item->id) }}">
                                <i class="fa-solid fa-trash fs-6"></i>
                            </button>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center text-muted">No news found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if ($news->hasPages())
    <div class="mt-3">
        {{ $news->appends([
                'q' => request('q'),
                'status' => request('status'),
            ])->links() }}
    </div>
@endif
