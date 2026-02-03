<div class="modal fade" id="publicationsModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Publications</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <input type="hidden" id="pubMemberId" value="">
                <div id="publicationsLoader" class="py-4 text-center d-none">
                    <i class="fa fa-spinner fa-spin me-2"></i> Loading publications...
                </div>

                <div id="publicationsContent"></div>

                <hr>

                <h6 class="fw-bold mb-3">Add New Publication</h6>

                <form method="POST" id="createPublicationForm">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Title</label>
                            <input type="text" name="title" class="form-control" placeholder="Publication title"
                                required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Type</label>
                            <select name="type" class="form-select">
                                <option value="">--</option>
                                <option value="journal">Journal</option>
                                <option value="conference">Conference</option>
                            </select>
                        </div>

                        <div class="col-md-5">
                            <label class="form-label fw-semibold">Journal / Conference Name</label>
                            <input type="text" name="journal_or_conference_name" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Publisher</label>
                            <input type="text" name="publisher" class="form-control">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Year</label>
                            <input type="number" name="year" class="form-control" min="1900" max="2100">
                        </div>

                        <div class="col-md-5">
                            <label class="form-label fw-semibold">DOI</label>
                            <input type="text" name="doi" class="form-control">
                        </div>

                        <div class="col-md-5">
                            <label class="form-label fw-semibold">URL</label>
                            <input type="text" name="url" class="form-control" placeholder="https://...">
                        </div>

                        <div class="col-md-12 d-flex justify-content-end">
                            <button class="btn btn-primary btn-sm">
                                <i class="fa fa-plus me-2"></i>Add Publication
                            </button>
                        </div>
                    </div>
                </form>

                <div class="text-muted small mt-3">
                    Tip: You can sort publications via drag/drop (if you enable Sortable in the JS below).
                </div>
            </div>
        </div>
    </div>
</div>
