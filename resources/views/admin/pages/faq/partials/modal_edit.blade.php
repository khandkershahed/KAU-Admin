<div class="modal-header">
    <h5 class="modal-title">Edit FAQ</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
    <form id="faqEditForm">

        @csrf
        @method('PUT')

        {{-- Question --}}
        <div class="mb-4">
            <label class="fw-bold mb-2">Question</label>
            <input type="text" name="question" class="form-control"
                   value="{{ $faq->question }}" required>
        </div>

        {{-- Answer --}}
        <div class="mb-4">
            <label class="fw-bold mb-2">Answer</label>
            <textarea name="answer" class="form-control" rows="4" required>{{ $faq->answer }}</textarea>
        </div>

        {{-- Category --}}
        <div class="mb-4">
            <label class="fw-bold mb-2">Category</label>
            <select name="category" class="form-select faq-category-select2">
                <option value="{{ $faq->category }}" selected>{{ $faq->category }}</option>
            </select>
        </div>

        {{-- Status --}}
        <div class="mb-4">
            <label class="fw-bold mb-2">Status</label>
            <select name="status" class="form-select" required>
                <option value="active" {{ $faq->status == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ $faq->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

    </form>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
    <button type="button" id="updateFaqBtn" data-id="{{ $faq->id }}" class="btn btn-primary">
        Update FAQ
    </button>
</div>
