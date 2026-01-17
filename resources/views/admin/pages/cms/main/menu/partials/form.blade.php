@php
    $isEdit = !empty($item);
@endphp

<div class="row g-6">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title fw-bold">Menu Item</h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <x-metronic.input name="label" label="Label" :value="old('label', $item->label ?? '')" required />
                        <div class="form-text">Shown on frontend navigation.</div>
                    </div>

                    <div class="col-md-6">
                        <x-metronic.input name="slug" label="Slug" :value="old('slug', $item->slug ?? '')" required />
                        <div class="form-text">Used for internal page URLs. Example: <code>about</code></div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Menu Location</label>
                        @php $loc = old('menu_location', $location ?? ($item->menu_location ?? 'navbar')); @endphp
                        <select name="menu_location" class="form-select form-select-sm" required>
                            <option value="navbar" @selected($loc==='navbar')>Navbar</option>
                            <option value="topbar" @selected($loc==='topbar')>Topbar</option>
                        </select>
                        <div class="form-text">Choose where this item appears.</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Type</label>
                        @php $type = old('type', $item->type ?? 'page'); @endphp
                        <select name="type" class="form-select form-select-sm" required>
                            <option value="page" @selected($type==='page')>Page ( /page/[slug] )</option>
                            <option value="group" @selected($type==='group')>Group (non-clickable)</option>
                            <option value="external" @selected($type==='external')>External Link</option>
                            <option value="route" @selected($type==='route')>Route ( /[slug] )</option>
                        </select>
                        <div class="form-text">For most main content pages, use <code>page</code>.</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Parent</label>
                        @php $pid = old('parent_id', $item->parent_id ?? ''); @endphp
                        <select name="parent_id" class="form-select form-select-sm">
                            <option value="">-- Root --</option>
                            @foreach($parents as $p)
                                <option value="{{ $p->id }}" @selected((string)$pid === (string)$p->id)>{{ $p->label }}</option>
                            @endforeach
                        </select>
                        <div class="form-text">Optional. Creates dropdown / mega grouping.</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Layout (only if item has children)</label>
                        @php $layout = old('layout', $item->layout ?? ''); @endphp
                        <select name="layout" class="form-select form-select-sm">
                            <option value="" @selected($layout==='')>Auto (dropdown)</option>
                            <option value="dropdown" @selected($layout==='dropdown')>Dropdown</option>
                            <option value="mega" @selected($layout==='mega')>Mega Menu</option>
                        </select>
                        <div class="form-text">Use <code>mega</code> for your mega menu layout.</div>
                    </div>

                    <div class="col-md-6">
                        <x-metronic.input name="menu_key" label="Menu Key (Optional)" :value="old('menu_key', $item->menu_key ?? '')" />
                        <div class="form-text">Optional value used by frontend for special links (example: gallery slug).</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Status</label>
                        @php $st = old('status', $item->status ?? 'published'); @endphp
                        <select name="status" class="form-select form-select-sm" required>
                            <option value="published" @selected($st==='published')>Published</option>
                            <option value="draft" @selected($st==='draft')>Draft</option>
                            <option value="archived" @selected($st==='archived')>Archived</option>
                        </select>
                        <div class="form-text">Draft items should not be shown on frontend.</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold mb-2">Icon (Optional)</label>
                        <x-metronic.icon-picker name="icon" :value="old('icon', $item->icon ?? '')" />
                        <div class="form-text">Used in some templates to show icons beside menu items.</div>
                    </div>

                    <div class="col-12" id="externalUrlWrap" style="display:none;">
                        <x-metronic.input name="external_url" label="External URL" :value="old('external_url', $item->external_url ?? '')" />
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="alert alert-info">
            <strong>Hint:</strong>
            For main dynamic pages, set <code>type=page</code> then create a matching Main Page using this menu item.
        </div>
    </div>
</div>

<script>
    (function(){
        function toggleExternal(){
            const type = (document.querySelector('select[name=type]') || {}).value || 'page';
            const el = document.getElementById('externalUrlWrap');
            if (!el) return;
            el.style.display = (type === 'external') ? 'block' : 'none';
        }
        document.addEventListener('change', function(e){
            if (e.target && e.target.matches('select[name=type]')) toggleExternal();
        });
        document.addEventListener('DOMContentLoaded', toggleExternal);
    })();
</script>
