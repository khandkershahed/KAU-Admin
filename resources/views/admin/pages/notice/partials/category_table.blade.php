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
                        <span class="badge badge-light-{{ $category->status === 'active' ? 'success' : 'danger' }}">
                            {{ ucfirst($category->status ?? 'inactive') }}
                        </span>
                    </td>
                    <td class="text-end">
                        @can('edit notice category')
                            <button type="button" class="btn btn-icon btn-light-success btn-sm" data-bs-toggle="modal"
                                data-bs-target="#editNoticeCategoryModal-{{ $category->id }}">
                                <i class="fa-solid fa-pen-to-square fs-6"></i>
                            </button>
                        @endcan
                        <div class="modal fade" id="editNoticeCategoryModal-{{ $category->id }}" tabindex="-1"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <form id="editCategoryForm" method="POST"
                                    action="{{ route('admin.notice-category.update', $category->id) }}"
                                    class="modal-content">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Notice Category</h5>
                                        <button type="button" class="btn btn-sm btn-icon" data-bs-dismiss="modal">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-5">
                                            <x-metronic.label for="edit_category_name"
                                                class="col-form-label fw-bold fs-6">
                                                Name
                                            </x-metronic.label>
                                            <x-metronic.input id="edit_category_name" type="text" name="name"
                                                value="{{ $category->name }}" required />
                                        </div>
                                        {{-- 'view_type', ['page', 'table'] --}}
                                        <div class="mb-5">
                                            <x-metronic.label for="edit_category_view_type"
                                                class="col-form-label fw-bold fs-6">
                                                View Type
                                            </x-metronic.label>
                                            <x-metronic.select-option id="edit_category_view_type" name="view_type"
                                                data-hide-search="true">
                                                <option value="page"
                                                    {{ $category->view_type == 'page' ? 'selected' : '' }}>Page</option>
                                                <option value="table"
                                                    {{ $category->view_type == 'table' ? 'selected' : '' }}>Table
                                                </option>
                                            </x-metronic.select-option>
                                        </div>
                                        <div class="mb-5">
                                            <x-metronic.label for="edit_category_status"
                                                class="col-form-label fw-bold fs-6">
                                                Status
                                            </x-metronic.label>
                                            <x-metronic.select-option id="edit_category_status" name="status"
                                                data-hide-search="true">
                                                <option value="active"
                                                    {{ $category->status == 'active' ? 'selected' : '' }}>Active
                                                </option>
                                                <option value="inactive"
                                                    {{ $category->status == 'inactive' ? 'selected' : '' }}>Inactive
                                                </option>
                                            </x-metronic.select-option>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light"
                                            data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Update Category</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @can('delete notice category')
                            <form action="{{ route('admin.notice-category.destroy', $category->id) }}" method="POST"
                                class="d-inline" onsubmit="return confirm('Delete this category?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-icon btn-light-danger btn-sm">
                                    <i class="fa-solid fa-trash fs-6"></i>
                                </button>
                            </form>
                        @endcan
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
