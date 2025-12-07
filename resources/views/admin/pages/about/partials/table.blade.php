<div class="table-responsive">
    <table class="table table-row-bordered table-hover align-middle">
        <thead style="background: beige;">
            <tr>
                <th style="width:50px;">Sort</th>
                <th>Title</th>
                <th>Slug</th>
                <th>Featured</th>
                <th>Status</th>
                <th style="width: 140px;" class="text-end">Actions</th>
            </tr>
        </thead>

        <tbody id="aboutPagesTbody">
            @forelse($pages as $page)
                <tr class="sort-row" data-id="{{ $page->id }}">
                    <td class="sort-handle" style="cursor: grab;">
                        <i class="fa-solid fa-up-down text-muted"></i>
                    </td>
                    <td style="cursor: grab;">{{ $page->title }}</td>
                    <td style="cursor: grab;">{{ $page->slug }}</td>

                    <td>
                        @can('edit about page')
                            <div class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input js-about-feature-toggle" type="checkbox"
                                    id="featured-{{ $page->id }}"
                                    data-url="{{ route('admin.about.toggle-featured', $page->id) }}"
                                    {{ $page->is_featured ? 'checked' : '' }}>
                                <label class="form-check-label" for="featured-{{ $page->id }}">
                                    {{ $page->is_featured ? 'Yes' : 'No' }}
                                </label>
                            </div>
                        @else
                            {{ $page->is_featured ? 'Yes' : 'No' }}
                        @endcan
                    </td>

                    <td>
                        @can('edit about page')
                            <select class="form-select form-select-sm js-about-status-select"
                                data-url="{{ route('admin.about.toggle-status', $page->id) }}"
                                data-prev="{{ $page->status }}">
                                <option value="draft" {{ $page->status === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ $page->status === 'published' ? 'selected' : '' }}>Published
                                </option>
                                <option value="archived" {{ $page->status === 'archived' ? 'selected' : '' }}>Archived
                                </option>
                            </select>
                        @else
                            {{ ucfirst($page->status) }}
                        @endcan
                    </td>

                    <td class="text-end">
                        @can('edit about page')
                            <a href="{{ route('admin.about.edit', $page->id) }}" class="btn btn-light-success btn-sm me-2">
                                <i class="fa-solid fa-pen fs-6"></i>
                            </a>
                        @endcan

                        @can('delete about page')
                            {{-- DELETE pattern as requested --}}
                            <a href="{{ route('admin.about.destroy', $page->id) }}" class="delete">
                                <i class="fa-solid fa-trash text-danger fs-4"></i>
                            </a>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No pages found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $pages->withQueryString()->links() }}
</div>
