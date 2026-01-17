<div class="card mt-6">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div>
            <h3 class="card-title fw-bold mb-0">Page Builder Blocks</h3>
            <div class="text-muted small mt-1">Add, reorder, and edit blocks. These are saved into <code>academic_page_blocks</code>.</div>
        </div>

        <div class="d-flex gap-2 align-items-center">
            <select id="blockTypeSelect" class="form-select form-select-sm" style="min-width:240px">
                <option value="rich_text">Rich Text</option>
                <option value="html">Custom HTML</option>
                <option value="cta">CTA Banner</option>
                <option value="stats">Stats</option>
                <option value="embed">Embed (iframe URL)</option>
            </select>
            <button type="button" id="btnAddBlock" class="btn btn-sm btn-primary">
                <i class="fa fa-plus me-2"></i>Add Block
            </button>
        </div>
    </div>

    <div class="card-body">
        <div class="alert alert-info py-3">
            <div class="fw-semibold">How blocks map to frontend</div>
            <div class="small mt-1">
                Blocks are rendered by the selected <b>template_key</b> in Next.js. Reorder blocks using drag &amp; drop, then edit each block inline.
            </div>
        </div>

        <ul id="blocksSortable" class="list-group">
            @forelse($page->blocks as $block)
                @php
                    $data = is_array($block->data) ? $block->data : [];
                @endphp

                <li class="list-group-item mb-3 block-item" data-id="{{ $block->id }}">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <span class="me-3 cursor-move sort-handle" title="Drag to reorder">
                                <i class="fas fa-grip-vertical fs-3 text-muted"></i>
                            </span>

                            <div>
                                <div class="fw-semibold">
                                    <span class="badge badge-light-primary me-2">{{ strtoupper($block->block_type) }}</span>
                                    <span class="text-muted">Block #{{ $block->id }}</span>
                                </div>
                                <div class="text-muted small">Status: <span class="fw-semibold">{{ $block->status ?? 'published' }}</span> &nbsp; | &nbsp; Position: <span class="fw-semibold">{{ $block->position }}</span></div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="btn btn-sm btn-light toggle-block" data-target="#blockBody{{ $block->id }}">
                                <i class="fa fa-chevron-down"></i>
                            </button>

                            <a href="{{ route('admin.academic.pages.blocks.destroy', [$page->id, $block->id]) }}" class="btn btn-sm btn-light-danger delete-block">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </div>

                    <div id="blockBody{{ $block->id }}" class="mt-4" style="display:none;">
                        <form class="block-update-form" action="{{ route('admin.academic.pages.blocks.update', [$page->id, $block->id]) }}" method="POST">
                            @csrf

                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Status</label>
                                    <select name="status" class="form-select form-select-sm">
                                        <option value="published" @if(($block->status ?? 'published') === 'published') selected @endif>Published</option>
                                        <option value="draft" @if(($block->status ?? '') === 'draft') selected @endif>Draft</option>
                                        <option value="archived" @if(($block->status ?? '') === 'archived') selected @endif>Archived</option>
                                    </select>
                                    <small class="text-muted">Inactive blocks will not render on frontend.</small>
                                </div>
                            </div>

                            {{-- BLOCK TYPE FORMS --}}
                            @if($block->block_type === 'rich_text')
                                <x-metronic.editor name="data[content]" label="Rich Text Content" :value="old('data.content', $data['content'] ?? '')" rows="12" />
                                <small class="text-muted">This content renders as a normal section block.</small>

                            @elseif($block->block_type === 'html')
                                <label class="form-label fw-bold">Custom HTML</label>
                                <textarea name="data[html]" rows="10" class="form-control form-control-sm">{{ old('data.html', $data['html'] ?? '') }}</textarea>
                                <small class="text-muted">Use carefully. Prefer Rich Text where possible.</small>

                            @elseif($block->block_type === 'cta')
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label fw-bold">Headline</label>
                                        <input type="text" name="data[headline]" class="form-control form-control-sm" value="{{ old('data.headline', $data['headline'] ?? '') }}">
                                        <small class="text-muted">Frontend CTA heading.</small>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label fw-bold">Subtext</label>
                                        <input type="text" name="data[subtext]" class="form-control form-control-sm" value="{{ old('data.subtext', $data['subtext'] ?? '') }}">
                                        <small class="text-muted">Short supporting line.</small>
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <label class="form-label fw-bold">Button Label</label>
                                        <input type="text" name="data[button_label]" class="form-control form-control-sm" value="{{ old('data.button_label', $data['button_label'] ?? '') }}">
                                    </div>
                                    <div class="col-md-8 mb-4">
                                        <label class="form-label fw-bold">Button URL</label>
                                        <input type="text" name="data[button_url]" class="form-control form-control-sm" value="{{ old('data.button_url', $data['button_url'] ?? '') }}">
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <label class="form-label fw-bold">Background Color</label>
                                        <x-metronic.color-picker name="data[bg_color]" :value="old('data.bg_color', $data['bg_color'] ?? '#f5f8fa')" />
                                        <small class="text-muted">Used by CTA template.</small>
                                    </div>
                                </div>

                            @elseif($block->block_type === 'stats')
                                <div class="alert alert-warning py-2">Enter up to 4 stats (label + value).</div>
                                @for($i=0; $i<4; $i++)
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Label #{{ $i+1 }}</label>
                                            <input type="text" name="data[items][{{ $i }}][label]" class="form-control form-control-sm" value="{{ old('data.items.'.$i.'.label', $data['items'][$i]['label'] ?? '') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Value #{{ $i+1 }}</label>
                                            <input type="text" name="data[items][{{ $i }}][value]" class="form-control form-control-sm" value="{{ old('data.items.'.$i.'.value', $data['items'][$i]['value'] ?? '') }}">
                                        </div>
                                    </div>
                                @endfor

                            @elseif($block->block_type === 'embed')
                                <label class="form-label fw-bold">Embed URL (iframe source)</label>
                                <input type="text" name="data[url]" class="form-control form-control-sm" value="{{ old('data.url', $data['url'] ?? '') }}">
                                <small class="text-muted">Example: YouTube embed URL or Google Map embed URL.</small>
                            @endif

                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" class="btn btn-sm btn-success">
                                    <i class="fa fa-save me-2"></i>Save Block
                                </button>
                            </div>
                        </form>
                    </div>
                </li>
            @empty
                <li class="list-group-item text-muted small">No blocks added yet.</li>
            @endforelse
        </ul>

        <div class="mt-5">
            <small class="text-muted">
                Tip: Drag blocks using the grip icon. Saving block order happens automatically after you drop.
            </small>
        </div>
    </div>
</div>
