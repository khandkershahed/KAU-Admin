<div class="table-responsive">
    <table class="table table-row-bordered align-middle border">
        <thead class="fw-bold" style="background: #f5f8fa;">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Status</th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($categories as $category)
                <tr>
                    <td>{{ $loop->iteration + ($categories->firstItem() - 1) }}</td>
                    <td>{{ $category->name }}</td>
                    <td>
                        <span
                            class="badge badge-light-{{ $category->status === 'active' ? 'success' : 'danger' }}">
                            {{ ucfirst($category->status ?? 'inactive') }}
                        </span>
                    </td>
                    <td class="text-end">
                        @unless ($category->slug === 'noc')
                            @can('edit notice category')
                                <button type="button"
                                    class="btn btn-icon btn-light-success btn-sm editCategoryBtn"
                                    data-id="{{ $category->id }}"
                                    data-name="{{ $category->name }}"
                                    data-status="{{ $category->status ?? 'active' }}">
                                    <i class="fa-solid fa-pen-to-square fs-6"></i>
                                </button>
                            @endcan

                            @can('delete notice category')
                                <form action="{{ route('admin.notice-category.destroy', $category->id) }}"
                                    method="POST" class="d-inline"
                                    onsubmit="return confirm('Delete this category?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-icon btn-light-danger btn-sm">
                                        <i class="fa-solid fa-trash fs-6"></i>
                                    </button>
                                </form>
                            @endcan
                        @endunless
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-muted text-center">No categories found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if ($categories->hasPages())
    <div class="mt-3">
        {{ $categories->appends(['category_search' => request('category_search')])->links() }}
    </div>
@endif
