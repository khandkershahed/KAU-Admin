<div class="modal-header">
    <h5 class="modal-title">Add FAQ</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
    <form id="faqCreateForm">

        @csrf

        {{-- Question --}}
        <div class="mb-4">
            <label class="fw-bold mb-2">Question</label>
            <input type="text" name="question" class="form-control" placeholder="Enter question" required>
        </div>

        {{-- Answer --}}
        <div class="mb-4">
            <label class="fw-bold mb-2">Answer</label>
            <textarea name="answer" class="form-control" rows="4" placeholder="Enter answer" required></textarea>
        </div>

        {{-- Category (Select2 AJAX) --}}
        <div class="mb-4">
            <label class="fw-bold mb-2">Category</label>
            <select name="category" class="form-select faq-category-select2"></select>
        </div>

        {{-- Status --}}
        <div class="mb-4">
            <label class="fw-bold mb-2">Status</label>
            <select name="status" class="form-select" required>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>

    </form>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
    <button type="button" id="saveFaqBtn" class="btn btn-primary">Save FAQ</button>
</div>
