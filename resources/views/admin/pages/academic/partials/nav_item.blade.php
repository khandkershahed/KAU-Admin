<li class="list-group-item nav-item-row mb-2 d-flex align-items-center justify-content-between"
    data-id="{{ $node['model']->id }}" style="cursor: grab;">

    <div class="d-flex align-items-center">
        <span class="me-3 nav-handle">
            <i class="fa-solid fa-up-down text-muted"></i>
        </span>

        <span class="fw-semibold">{{ $node['model']->label }}</span>

        @if ($node['model']->menu_key)
            <span class="badge bg-light text-muted ms-2">{{ $node['model']->menu_key }}</span>
        @endif

        @if ($node['model']->type === 'route')
            <small class="text-muted ms-2">/ {{ $node['model']->route_path }}</small>
        @endif
        @if ($node['model']->type === 'external')
            <small class="text-muted ms-2">→ {{ $node['model']->external_url }}</small>
        @endif
        @if ($node['model']->type === 'page' && $node['model']->page)
            <small class="text-muted ms-2">{{ $node['model']->page->title }}</small>
        @endif
    </div>

    <div class="d-flex align-items-center">
        {{-- Edit Button --}}
        <button class="btn btn-light-success btn-sm editNavItemBtn me-2" data-id="{{ $node['model']->id }}"
            data-label="{{ $node['model']->label }}" data-menu_key="{{ $node['model']->menu_key }}"
            data-type="{{ $node['model']->type }}" data-page_id="{{ $node['model']->page_id }}"
            data-route_path="{{ $node['model']->route_path }}" data-external_url="{{ $node['model']->external_url }}"
            data-parent_id="{{ $node['model']->parent_id }}" data-icon="{{ $node['model']->icon }}"
            data-active="{{ $node['model']->is_active ? 1 : 0 }}">
            <i class="fa-solid fa-pen-to-square fs-6"></i>
        </button>

        {{-- Delete --}}
        <a href="{{ route('admin.academic.nav.destroy', $node['model']->id) }}" class="delete">
            <i class="fa-solid fa-trash text-danger fs-4"></i>
        </a>
    </div>
</li>

{{-- Render children --}}
@if (!empty($node['children']))
    <ul class="list-group ms-5 mt-2">
        @foreach ($node['children'] as $child)
            @include('admin.pages.academic.partials.nav_item', [
                'node' => $child,
                'level' => ($level ?? 0) + 1,
            ])
        @endforeach
    </ul>
@endif
