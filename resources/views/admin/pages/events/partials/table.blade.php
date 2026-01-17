<div class="table-responsive">
    <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
        <thead>
            <tr class="fw-bold text-muted">
                <th>Title</th>
                <th>Schedule</th>
                <th>Venue</th>
                <th>Status</th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($events as $event)
                <tr>
                    <td>
                        <div class="d-flex flex-column">
                            <span class="fw-semibold">{{ $event->title }}</span>
                            <span class="text-muted fs-8">/{{ $event->slug }}</span>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex flex-column">
                            <span class="fw-semibold fs-8">{{ $event->start_at ? $event->start_at->format('d M Y, h:i A') : '-' }}</span>
                            <span class="text-muted fs-8">{{ $event->end_at ? $event->end_at->format('d M Y, h:i A') : '' }}</span>
                        </div>
                    </td>
                    <td class="text-muted">{{ $event->venue ?? '-' }}</td>
                    <td>
                        <span class="badge badge-light-{{ $event->status === 'published' ? 'success' : ($event->status === 'draft' ? 'warning' : 'secondary') }}">
                            {{ ucfirst($event->status) }}
                        </span>
                    </td>
                    <td class="text-end">
                        <a href="{{ route('admin.events.edit', $event->id) }}" class="btn btn-light btn-sm me-1">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="{{ route('admin.events.destroy', $event->id) }}" class="delete">
                            <i class="fa-solid fa-trash text-danger fs-4"></i>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-10">No events found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-end">
    {!! $events->appends(['q' => $search, 'status' => $status])->onEachSide(1)->links() !!}
</div>

@push('scripts')
<script>
    // mark pagination links for ajax
    document.querySelectorAll('#table_container .pagination a').forEach(a => a.setAttribute('data-ajax-pagination','1'));
</script>
@endpush
