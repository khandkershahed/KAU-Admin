<div class="table-responsive">
    <table class="table table-row-bordered align-middle border">
        <thead class="fw-bold" style="background: #f5f8fa;">
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 35%;">Title</th>
                <th style="width: 15%;">Category</th>
                <th style="width: 15%;">Publish Date</th>
                <th style="width: 10%;">Featured</th>
                <th style="width: 10%;">Status</th>
                <th class="text-end" style="width: 10%;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($notices as $notice)
                <tr>
                    <td>{{ $loop->iteration + ($notices->firstItem() - 1) }}</td>

                    <td>{{ $notice->title }}</td>

                    <td>{{ optional($notice->noticeCategory)->name ?? '-' }}</td>

                    <td>{{ $notice->publish_date?->format('Y-m-d') ?? '-' }}</td>

                    {{-- FEATURED TOGGLER --}}
                    <td>
                        @can('edit notice')
                            <div class="form-check form-switch form-switch-sm">
                                <input type="checkbox" class="form-check-input js-notice-feature-toggle"
                                    id="noticeFeatured{{ $notice->id }}"
                                    data-url="{{ route('admin.notice.toggle-featured', $notice->id) }}"
                                    {{ $notice->is_featured ? 'checked' : '' }}>
                                <label class="form-check-label small" for="noticeFeatured{{ $notice->id }}">
                                    {{ $notice->is_featured ? 'Yes' : 'No' }}
                                </label>
                            </div>
                        @else
                            @if ($notice->is_featured)
                                <span class="badge badge-light-warning">Yes</span>
                            @else
                                <span class="badge badge-light">No</span>
                            @endif
                        @endcan
                    </td>

                    {{-- STATUS TOGGLER --}}
                    <td>
                        @can('edit notice')
                            @php
                                $statusValue = old('status', $notice->status);
                            @endphp
                            <select class="form-select form-select-sm js-notice-status-select"
                                data-url="{{ route('admin.notice.toggle-status', $notice->id) }}" style="min-width: 115px;">
                                <option value="draft" {{ $statusValue === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ $statusValue === 'published' ? 'selected' : '' }}>Published
                                </option>
                                <option value="archived" {{ $statusValue === 'archived' ? 'selected' : '' }}>Archived
                                </option>
                            </select>
                        @else
                            <span
                                class="badge badge-light-{{ $notice->status === 'published' ? 'success' : ($notice->status === 'draft' ? 'secondary' : 'danger') }}">
                                {{ ucfirst($notice->status) }}
                            </span>
                        @endcan
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
