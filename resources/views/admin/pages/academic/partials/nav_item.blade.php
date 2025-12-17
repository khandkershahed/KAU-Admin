@php
    $children = $item->children ?? collect();
@endphp

<li class="list-group-item nav-item-row mb-2 d-flex align-items-center justify-content-between"
    data-id="{{ $item->id }}" style="cursor:grab;">

    <div class="d-flex align-items-center">
        <span class="me-3 nav-handle">
            <i class="fa-solid fa-up-down text-muted"></i>
        </span>

        <div class="d-flex flex-column">
            <span class="fw-semibold">
                {{ $item->label }}
                @if ($item->status !== 'published')
                    <span class="badge bg-secondary ms-2">{{ ucfirst($item->status) }}</span>
                @endif
            </span>

            {{-- <div class="small text-muted">
                slug: <code>{{ $item->slug }}</code>
                @if ($item->menu_key)
                    &nbsp; | key: <code>{{ $item->menu_key }}</code>
                @endif
                &nbsp; | type: <span>{{ $item->type }}</span>
                @if ($item->type === 'external' && $item->external_url)
                    &nbsp; → <span>{{ $item->external_url }}</span>
                @endif
            </div> --}}
        </div>
    </div>

    <div class="d-flex align-items-center">
        <button type="button" class="btn btn-light-success btn-sm me-2 editNavItemBtn"
            data-id="{{ $item->id }}"
            data-label="{{ $item->label }}"
            data-slug="{{ $item->slug }}"
            data-menu_key="{{ $item->menu_key }}"
            data-type="{{ $item->type }}"
            data-external_url="{{ $item->external_url }}"
            data-icon="{{ $item->icon }}"
            data-status="{{ $item->status }}"
            data-parent_id="{{ $item->parent_id }}">
            <i class="fa-solid fa-pen-to-square fs-6"></i>
        </button>

        <a href="{{ route('admin.academic.nav.destroy', $item->id) }}" class="delete">
            <i class="fa-solid fa-trash text-danger fs-4"></i>
        </a>
    </div>

</li>

@if ($children->count() > 0)
    <ul class="list-group ms-5 mt-2">
        @foreach ($children as $child)
            @include('admin.pages.academic.partials.nav_item', [
                'item' => $child,
                'site' => $site,
            ])
        @endforeach
    </ul>
@endif
