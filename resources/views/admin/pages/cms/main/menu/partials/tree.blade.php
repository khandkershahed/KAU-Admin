<ul class="list-group ms-{{ $parent ? 4 : 0 }}">
    @foreach ($items->where('parent_id', $parent?->id) as $item)
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <div>
                <strong>{{ $item->label }}</strong>
                <small class="text-muted">({{ $item->type }})</small>
            </div>

            <div>
                <a href="{{ route('admin.cms.main.menu.create', ['parent_id' => $item->id]) }}"
                    class="btn btn-sm btn-light-primary me-1">
                    <i class="fa fa-plus"></i>
                </a>

                <a href="{{ route('admin.cms.main.menu.edit', $item->id) }}" class="btn btn-sm btn-light-success me-1">
                    <i class="fa fa-edit"></i>
                </a>

                <a href="{{ route('admin.cms.main.menu.destroy', $item->id) }}" class="delete btn btn-sm btn-light-danger">
                    <i class="fa fa-trash"></i>
                </a>
            </div>
        </li>

        @include('admin.pages.cms.main.menu.partials.tree', ['items' => $items, 'parent' => $item])
    @endforeach
</ul>
