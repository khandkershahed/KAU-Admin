<div class="table-responsive">
    <table class="table table-row-bordered align-middle">
        <thead class="fw-bold" style="background:#f5f8fa;">
            <tr>
                <th width="5%">#</th>
                <th width="44%">Title</th>
                <th width="12%">Published At</th>
                <th width="13%">Featured</th>
                <th width="13%">Status</th>
                <th width="13%" class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($news as $item)
                <tr>
                    <td>{{ $loop->iteration + ($news->firstItem() - 1) }}</td>
                    <td>{{ $item->title }}</td>
                    {{-- <td>{{ $item->category ?? '-' }}</td>
                    <td>{{ $item->author ?? '-' }}</td> --}}
                    <td>{{ $item->published_at?->format('Y-m-d') ?? '-' }}</td>

                    {{-- FEATURED TOGGLER --}}
                    <td>
                        @can('edit news')
                            <div class="form-check form-switch form-switch-sm">
                                <input type="checkbox"
                                       class="form-check-input js-feature-toggle"
                                       id="featuredSwitch{{ $item->id }}"
                                       data-url="{{ route('admin.news.toggle-featured', $item->id) }}"
                                       {{ $item->is_featured ? 'checked' : '' }}>
                                <label class="form-check-label small" for="featuredSwitch{{ $item->id }}">
                                    {{ $item->is_featured ? 'Yes' : 'No' }}
                                </label>
                            </div>
                        @else
                            @if ($item->is_featured)
                                <span class="badge badge-light-warning">Yes</span>
                            @else
                                <span class="badge badge-light">No</span>
                            @endif
                        @endcan
                    </td>

                    {{-- STATUS TOGGLER (DROPDOWN) --}}
                    <td>
                        @can('edit news')
                            @php
                                $statusValue = old('status', $item->status);
                            @endphp
                            <select class="form-select form-select-sm js-status-select"
                                    data-url="{{ route('admin.news.toggle-status', $item->id) }}"
                                    style="min-width: 115px;">
                                <option value="draft" {{ $statusValue === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ $statusValue === 'published' ? 'selected' : '' }}>Published</option>
                                <option value="unpublished" {{ $statusValue === 'unpublished' ? 'selected' : '' }}>Unpublished</option>
                            </select>
                        @else
                            <span
                                class="badge badge-light-{{ $item->status === 'published' ? 'success' : ($item->status === 'draft' ? 'secondary' : 'danger') }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        @endcan
                    </td>

                    <td class="text-end">
                        @can('edit news')
                            <a href="{{ route('admin.news.edit', $item->id) }}"
                                class="btn btn-icon btn-light-success btn-sm me-1">
                                <i class="fa-solid fa-pen-to-square fs-6"></i>
                            </a>
                        @endcan

                        @can('delete news')
                            <button type="button"
                                    class="btn btn-icon btn-light-danger btn-sm deleteNewsBtn"
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

