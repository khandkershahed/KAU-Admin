<!-- ======================================================
    PAGE 1 MODALS: GROUPS + OFFICES
======================================================= -->

<!-- ======================================================
    CREATE GROUP MODAL
======================================================= -->
<div class="modal fade" id="createGroupModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <form id="createGroupForm" action="{{ route('admin.administration.group.store') }}" method="POST" novalidate>
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Add New Group</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Group Name <span class="text-danger">*</span></label>

                        <input type="text" name="name" class="form-control form-control-sm"
                            placeholder="Enter group name..." required>

                        <span class="text-danger error-text name_error small"></span>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Group</button>
                </div>

            </form>

        </div>
    </div>
</div>



<!-- ======================================================
    EDIT GROUP MODAL
======================================================= -->
<div class="modal fade" id="editGroupModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <form id="editGroupForm" action="{{ route('admin.administration.group.update') }}" method="POST"
                novalidate>
                @csrf
                @method('PUT')

                <input type="hidden" name="id">

                <div class="modal-header">
                    <h5 class="modal-title">Edit Group</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Group Name <span class="text-danger">*</span></label>

                        <input type="text" name="name" class="form-control form-control-sm"
                            placeholder="Enter group name..." required>

                        <span class="text-danger error-text name_error small"></span>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Group</button>
                </div>

            </form>

        </div>
    </div>
</div>



<!-- ======================================================
    CREATE OFFICE MODAL
======================================================= -->
<div class="modal fade" id="createOfficeModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <form id="createOfficeForm" action="{{ route('admin.administration.office.store') }}" method="POST"
                novalidate>
                @csrf

                <input type="hidden" name="group_id" id="createOfficeGroupId">

                <div class="modal-header">
                    <h5 class="modal-title">Add New Office</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Office Title <span class="text-danger">*</span></label>

                        <input type="text" name="title" class="form-control form-control-sm"
                            placeholder="Enter office title..." required>

                        <span class="text-danger error-text title_error small"></span>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Group <span class="text-danger">*</span></label>

                        <select name="group_id" id="officeGroupSelect" class="form-select form-select-sm"
                            data-control="select2" data-placeholder="Select Group" required>

                            @foreach ($groups as $g)
                                <option value="{{ $g->id }}">{{ $g->name }}</option>
                            @endforeach

                        </select>

                        <span class="text-danger error-text group_id_error small"></span>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" class="form-control form-control-sm" rows="3"
                            placeholder="Enter description (optional)..."></textarea>
                    </div>

                    <!-- SEO -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Meta Title</label>
                        <input type="text" name="meta_title" class="form-control form-control-sm">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Meta Tags</label>
                        <input type="text" name="meta_tags" class="form-control form-control-sm">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-semibold">Meta Description</label>
                        <textarea name="meta_description" class="form-control form-control-sm" rows="2"></textarea>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Office</button>
                </div>

            </form>

        </div>
    </div>
</div>



<!-- ======================================================
    EDIT OFFICE MODAL
======================================================= -->
<div class="modal fade" id="editOfficeModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <form id="editOfficeForm" action="{{ route('admin.administration.office.update') }}" method="POST"
                novalidate>
                @csrf
                @method('PUT')

                <input type="hidden" name="id">

                <div class="modal-header">
                    <h5 class="modal-title">Edit Office</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Office Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control form-control-sm" required>
                        <span class="text-danger error-text title_error small"></span>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Group <span class="text-danger">*</span></label>

                        <select name="group_id" id="editOfficeGroupSelect" class="form-select form-select-sm"
                            data-control="select2" data-placeholder="Select Group" required>
                            @foreach ($groups as $g)
                                <option value="{{ $g->id }}">{{ $g->name }}</option>
                            @endforeach
                        </select>

                        <span class="text-danger error-text group_id_error small"></span>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" class="form-control form-control-sm" rows="3"></textarea>
                    </div>

                    <!-- SEO -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Meta Title</label>
                        <input type="text" name="meta_title" class="form-control form-control-sm">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Meta Tags</label>
                        <input type="text" name="meta_tags" class="form-control form-control-sm">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-semibold">Meta Description</label>
                        <textarea name="meta_description" class="form-control form-control-sm" rows="2"></textarea>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Office</button>
                </div>

            </form>

        </div>
    </div>
</div>



<!-- ======================================================
    REAL-TIME VALIDATION SCRIPT
======================================================= -->
<script>
    $(document).ready(function() {

        function applyValidation(formId, rules) {
            $(formId).validate({
                rules: rules,

                errorPlacement: function(error, element) {
                    let container = element.closest('div').find('.error-text');
                    if (container.length) container.html(error.text());
                    else error.insertAfter(element);
                },

                highlight: function(element) {
                    $(element).addClass('is-invalid');
                },

                unhighlight: function(element) {
                    $(element).removeClass('is-invalid');
                    $(element).closest('div').find('.error-text').html('');
                },

                submitHandler: function(form) {
                    form.submit();
                }
            });
        }

        applyValidation("#createGroupForm", {
            name: {
                required: true,
                maxlength: 255
            }
        });
        applyValidation("#editGroupForm", {
            name: {
                required: true,
                maxlength: 255
            }
        });

        applyValidation("#createOfficeForm", {
            title: {
                required: true,
                maxlength: 255
            },
            group_id: {
                required: true
            }
        });

        applyValidation("#editOfficeForm", {
            title: {
                required: true,
                maxlength: 255
            },
            group_id: {
                required: true
            }
        });

        $("select[data-control='select2']").on("change", function() {
            $(this).valid();
        });

    });
</script>
