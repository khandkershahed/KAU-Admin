<div class="table-responsive">
    <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
        <thead>
            <tr class="fw-bold text-muted">
                <th>Title</th>
                <th>Publish</th>
                <th>Closing</th>
                <th>Status</th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tenders as $tender)
                <tr>
                    <td>
                        <div class="d-flex flex-column">
                            <span class="fw-semibold">{{ $tender->title }}</span>
                            <span class="text-muted fs-8">{{ $tender->reference_no ?? '' }}</span>
                        </div>
                    </td>
                    <td class="text-muted">{{ $tender->publish_date ? $tender->publish_date->format('d M Y') : '-' }}</td>
                    <td class="text-muted">{{ $tender->closing_date ? $tender->closing_date->format('d M Y') : '-' }}</td>
                    <td>
                        <span class="badge badge-light-{{ $tender->status === 'published' ? 'success' : ($tender->status === 'draft' ? 'warning' : 'secondary') }}">
                            {{ ucfirst($tender->status) }}
                        </span>
                    </td>
                    <td class="text-end">
                        <a href="{{ route('admin.tenders.edit', $tender->id) }}" class="btn btn-light btn-sm me-1">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="{{ route('admin.tenders.destroy', $tender->id) }}" class="delete">
                            <i class="fa-solid fa-trash text-danger fs-4"></i>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-10">No tenders found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-end">
    {!! $tenders->appends(['q' => $search, 'status' => $status])->onEachSide(1)->links() !!}
</div>

@push('scripts')
<script>
    document.querySelectorAll('#table_container .pagination a').forEach(a => a.setAttribute('data-ajax-pagination','1'));
</script>
@endpush
