@php
    $children = $byParent[$item->id] ?? collect();
@endphp

<li class="list-group-item d-flex align-items-center justify-content-between menu-item-row" data-id="{{ $item->id }}">
    <div class="d-flex align-items-center">
        <span class="me-3 text-muted sort-handle" style="cursor: grab;">
            <i class="fa-solid fa-up-down"></i>
        </span>

        <div>
            <div class="fw-semibold">
                {{ $item->label }}

                @if($item->type === 'group')
                    <span class="badge badge-light">Group</span>
                @elseif($item->type === 'page')
                    <span class="badge badge-light-primary">Page</span>
                @elseif($item->type === 'external')
                    <span class="badge badge-light-warning">External</span>
                @else
                    <span class="badge badge-light-info">Route</span>
                @endif

                @if(!empty($item->layout))
                    <span class="badge badge-light-success">{{ strtoupper($item->layout) }}</span>
                @endif

                @if($item->status !== 'published')
                    <span class="badge badge-light-danger">{{ ucfirst($item->status) }}</span>
                @endif
            </div>

            <div class="text-muted">
                Slug: <code>{{ $item->slug }}</code>

                @if(!empty($item->external_url))
                    <span class="mx-2">•</span>
                    URL: <code>{{ $item->external_url }}</code>
                @endif
            </div>
        </div>
    </div>

    <div class="d-flex gap-2">
        <a href="{{ route('admin.cms.main.menu.edit', ['item' => $item->id, 'menu_location' => $location]) }}" class="btn btn-light-success btn-sm">
            <i class="fa-solid fa-pen-to-square"></i>
        </a>
        <a href="{{ route('admin.cms.main.menu.destroy', ['item' => $item->id]) }}" class="btn btn-light-danger btn-sm delete-main-menu">
            <i class="fa-solid fa-trash"></i>
        </a>
    </div>
</li>

@if($children->count())
    <li class="list-group-item border-0 pt-0">
        <ul class="list-group ms-10 menu-sortable" data-parent="{{ $item->id }}">
            @foreach($children as $child)
                @include('admin.pages.cms.main.menu.partials.item', ['item' => $child, 'byParent' => $byParent, 'location' => $location])
            @endforeach
        </ul>
    </li>
@endif
