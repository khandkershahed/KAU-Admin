<table class="table table-row-bordered align-middle gs-0 gy-3">
    <thead style="background-color: aliceblue;">
        <tr>
            <th class="text-center" style="width:50px;">⇅</th>
            <th>Question</th>
            <th>Category</th>
            <th>Status</th>
            <th>Featured</th>
            <th class="text-end" style="width:120px;">Actions</th>
        </tr>
    </thead>
    <tbody id="faqSortable">

        @foreach ($faqs as $faq)
            <tr data-id="{{ $faq->id }}" class="faq-row">

                {{-- Drag Handle --}}
                <td class="text-center cursor-pointer sort-handle" style="cursor: grab;">
                    <i class="fa-solid fa-up-down fs-4"></i>
                </td>

                <td>{{ $faq->question }}</td>

                <td>{{ $faq->category }}</td>

                {{-- Status Toggle --}}
                <td>
                    <select class="form-select form-select-sm js-status-toggle"
                        data-url="{{ route('admin.faq.toggle-status', $faq->id) }}">
                        <option value="active" {{ $faq->status == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ $faq->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </td>

                {{-- Featured Toggle --}}
                <td>
                    <div class="form-check form-switch">
                        <input type="checkbox" class="form-check-input js-feature-toggle"
                            data-url="{{ route('admin.faq.toggle-featured', $faq->id) }}"
                            {{ $faq->is_featured ? 'checked' : '' }}>
                    </div>
                </td>

                {{-- Actions --}}
                <td class="text-end">

                    @can('edit faq')
                        <a href="javascript:void(0);" class="me-3 editFaqBtn"
                            data-url="{{ route('admin.faq.edit', $faq->id) }}">
                            <i class="fa-solid fa-pen-to-square fs-4 text-primary"></i>
                        </a>
                    @endcan

                    @can('delete faq')
                        <a href="{{ route('admin.faq.destroy', $faq->id) }}" class="delete">
                            <i class="fa-solid fa-trash text-danger fs-4"></i>
                        </a>
                    @endcan

                </td>

            </tr>
        @endforeach

    </tbody>
</table>

<div class="mt-4">
    {{ $faqs->links() }}
</div>
