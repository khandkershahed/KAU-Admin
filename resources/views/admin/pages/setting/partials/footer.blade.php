
<div class="mb-10">
    <label class="form-label fw-bold">Footer Description</label>
    <textarea name="footer_description" rows="3" class="form-control">{{ $setting->footer_description }}</textarea>
</div>


<div class="mb-10">
    <label class="form-label fw-bold">Footer Links</label>

    <div id="footerLinksRepeater" class="repeater-wrapper" data-field="footer_links">
        @forelse($setting->footer_links ?? [] as $i => $item)
            <div class="repeater-row d-flex gap-2 align-items-center mb-2">

                <span class="sortable-handle cursor-pointer">☰</span>

                <input type="text" name="footer_links[{{ $i }}][title]" class="form-control form-control-sm"
                    placeholder="Title" value="{{ $item['title'] ?? '' }}">

                <input type="text" name="footer_links[{{ $i }}][url]" class="form-control form-control-sm"
                    placeholder="URL" value="{{ $item['url'] ?? '' }}">

                <button type="button" class="btn btn-danger btn-sm delete-row-btn">Delete</button>
            </div>
        @empty
        @endforelse
    </div>

    <button type="button" id="addFooterLinkBtn" class="btn btn-light-primary btn-sm mt-3">+ Add Link</button>
</div>


<div class="mb-10">
    <label class="form-label fw-bold">Contact Person</label>

    <div id="contactPersonRepeater" class="repeater-wrapper" data-field="contact_person">
        @foreach ($setting->contact_person ?? [] as $i => $p)
            <div class="repeater-row d-flex gap-2 align-items-center mb-7" style="flex-wrap: wrap;">

                <input type="text" name="contact_person[{{ $i }}][name]" class="form-control"
                    placeholder="Name" value="{{ $p['name'] ?? '' }}">
                <input type="text" name="contact_person[{{ $i }}][designation]" class="form-control"
                    placeholder="Designation" value="{{ $p['designation'] ?? '' }}">
                <input type="email" name="contact_person[{{ $i }}][email]" class="form-control"
                    placeholder="Email" value="{{ $p['email'] ?? '' }}">
                <input type="text" name="contact_person[{{ $i }}][phone]" class="form-control"
                    placeholder="Phone" value="{{ $p['phone'] ?? '' }}">

                <button type="button" class="btn btn-danger btn-sm delete-row-btn">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>
        @endforeach
    </div>

    <button type="button" id="addContactPersonBtn" class="btn btn-light-primary btn-sm mt-3">+ Add</button>
</div>



<div class="mb-10">
    <label class="form-label fw-bold">Copyright Text</label>
    <input type="text" name="copyright_text" class="form-control" value="{{ $setting->copyright_text }}">
</div>


<div class="mb-10">
    <label class="form-label fw-bold">Developer Text</label>
    <input type="text" name="developer_text" class="form-control" value="{{ $setting->developer_text }}">
</div>

<div class="mb-10">
    <label class="form-label fw-bold">Developer Link</label>
    <input type="text" name="developer_link" class="form-control" value="{{ $setting->developer_link }}">
</div>



