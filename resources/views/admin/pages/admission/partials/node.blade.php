{{-- @php
    use Illuminate\Support\Str;
    $children = $node->children()->orderBy('position', 'asc')->get();
@endphp

<div class="accordion-item admission-node mb-3" data-node-id="{{ $node->id }}"
    data-title="{{ Str::lower($node->title) }}">

    <div class="accordion-header d-flex align-items-center justify-content-between px-3 py-2"
        style="background: {{ $level === 0 ? 'aliceblue' : '#f5f8fa' }};">

        <div class="d-flex align-items-center flex-grow-1 me-2 node-sort-handle" style="cursor:grab;">

            <span class="me-3">
                <i class="fa-solid fa-up-down text-muted"></i>
            </span>

            <button class="accordion-button collapsed py-2 px-2 shadow-none bg-transparent flex-grow-1" type="button"
                data-bs-toggle="collapse" data-bs-target="#admission-node-{{ $node->id }}">
                <span class="fw-semibold">{{ $node->title }}</span>

                <span class="badge badge-light text-muted ms-2">
                    {{ $node->slug }}
                </span>

                <span
                    class="badge badge-{{ $node->type === 'menu' ? 'secondary' : ($node->type === 'page' ? 'info' : 'warning') }} ms-2">
                    {{ ucfirst($node->type) }}
                </span>

                @if ($children->count())
                    <span class="badge badge-light ms-2 text-muted">
                        {{ $children->count() }} child{{ $children->count() > 1 ? 'ren' : '' }}
                    </span>
                @endif
            </button>
        </div>

        <div class="d-flex align-items-center ms-3">
            @can('edit admission')
                <a href="{{ route('admin.admission.edit', $node->id) }}" class="btn btn-light-success btn-sm me-2">
                    <i class="fa-solid fa-pen-to-square fs-6"></i>
                </a>
            @endcan

            @can('delete admission')
                <form action="{{ route('admin.admission.destroy', $node->id) }}" method="POST"
                    onsubmit="return confirm('Delete this item?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-light-danger btn-sm">
                        <i class="fa-solid fa-trash fs-6"></i>
                    </button>
                </form>
            @endcan
        </div>
    </div>

    <div id="admission-node-{{ $node->id }}"
        class="accordion-collapse collapse @if ($level === 0 && $loop->first ?? false) show @endif">

        <div class="accordion-body">
            @if ($children->count())
                <div class="accordion node-sortable ms-{{ min(5, $level + 1) * 2 }}"
                    data-parent-id="{{ $node->id }}">
                    @foreach ($children as $child)
                        @include('admin.pages.admission.partials.node', [
                            'node' => $child,
                            'level' => $level + 1,
                        ])
                    @endforeach
                </div>
            @else
                <span class="text-muted fs-7">No child items.</span>
            @endif
        </div>
    </div>
</div> --}}


@php
    use Illuminate\Support\Str;

    // Direct children of this node
    $children = $node->children()->orderBy('position', 'asc')->get();

    // Check if any of the children has its own children (for nested accordion vs table)
    $hasNestedChildren = $children->contains(function ($child) {
        return $child->children()->exists();
    });
@endphp

<div class="accordion-item admission-node mb-3" data-node-id="{{ $node->id }}"
    data-title="{{ Str::lower($node->title) }}">

    {{-- HEADER --}}
    <div class="accordion-header d-flex align-items-center justify-content-between px-3 py-2"
        style="background: {{ $level === 0 ? 'aliceblue' : '#f5f8fa' }};">

        {{-- LEFT: sort handle + title --}}
        <div class="d-flex align-items-center flex-grow-1 me-2 node-sort-handle" style="cursor:grab;">

            <span class="me-3">
                <i class="fa-solid fa-up-down text-muted"></i>
            </span>

            <button class="accordion-button collapsed py-2 px-2 shadow-none bg-transparent flex-grow-1" type="button"
                data-bs-toggle="collapse" data-bs-target="#admission-node-{{ $node->id }}">
                <span class="fw-semibold">{{ $node->title }}</span>

                <span class="badge bg-light text-muted ms-2">
                    {{ $node->slug }}
                </span>

                <span
                    class="badge bg-{{ $node->type === 'menu' ? 'secondary' : ($node->type === 'page' ? 'info' : 'warning') }} ms-2">
                    {{ ucfirst($node->type) }}
                </span>

                @if ($children->count())
                    <span class="badge bg-light ms-2 text-muted">
                        {{ $children->count() }} child{{ $children->count() > 1 ? 'ren' : '' }}
                    </span>
                @endif
            </button>
        </div>

        {{-- RIGHT: actions --}}
        <div class="d-flex align-items-center ms-3">
            @can('edit admission')
                <a href="{{ route('admin.admission.edit', $node->id) }}" class="btn btn-light-success btn-sm me-2">
                    <i class="fa-solid fa-pen-to-square fs-6"></i>
                </a>
            @endcan

            @can('delete admission')
                <form action="{{ route('admin.admission.destroy', $node->id) }}" method="POST"
                    onsubmit="return confirm('Delete this item?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-light-danger btn-sm">
                        <i class="fa-solid fa-trash fs-6"></i>
                    </button>
                </form>
            @endcan
        </div>
    </div>

    {{-- BODY --}}
    <div id="admission-node-{{ $node->id }}"
        class="accordion-collapse collapse @if ($level === 0 && ($loop->first ?? false)) show @endif">

        <div class="accordion-body">

            {{-- CASE 1: Node has children --}}
            @if ($children->count())

                {{-- CASE 1A: Some children have their own children → nested accordions --}}
                @if ($hasNestedChildren)
                    <div class="accordion node-sortable ms-{{ min(5, $level + 1) * 2 }}"
                        data-parent-id="{{ $node->id }}">
                        @foreach ($children as $child)
                            @include('admin.pages.admission.partials.node', [
                                'node' => $child,
                                'level' => $level + 1,
                            ])
                        @endforeach
                    </div>

                    {{-- CASE 1B: All children are leaves → show as a sortable table list --}}
                @else
                    <div class="table-responsive ms-{{ min(5, $level + 1) * 2 }}">
                        <table class="table px-2 border table-row-bordered table-hover">
                            <thead style="background: beige;">
                                <tr class="fw-bold text-muted">
                                    <th style="width:40px;">Sort</th>
                                    <th>Title</th>
                                    <th>Slug</th>
                                    <th>Type</th>
                                    <th>External URL</th>
                                    <th style="width:140px;" class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="node-sortable" data-parent-id="{{ $node->id }}">
                                @foreach ($children as $child)
                                    <tr class="admission-node" data-node-id="{{ $child->id }}"
                                        data-title="{{ Str::lower($child->title) }}">
                                        <td class="node-sort-handle" style="cursor:grab;">
                                            <i class="fa-solid fa-up-down text-muted"></i>
                                        </td>
                                        <td class="node-sort-handle" style="cursor:grab;">{{ $child->title }}</td>
                                        <td class="node-sort-handle" style="cursor:grab;">{{ $child->slug }}</td>
                                        <td class="node-sort-handle" style="cursor:grab;">{{ ucfirst($child->type) }}</td>
                                        <td>
                                            @if ($child->external_url)
                                                <a href="{{ $child->external_url }}" target="_blank">
                                                    {{ Str::limit($child->external_url, 40) }}
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            @can('edit admission')
                                                <a href="{{ route('admin.admission.edit', $child->id) }}"
                                                    class="btn btn-icon btn-sm btn-light-success me-2">
                                                    <i class="fa-solid fa-pen-to-square fs-6"></i>
                                                </a>
                                            @endcan

                                            @can('delete admission')
                                                <form action="{{ route('admin.admission.destroy', $child->id) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Delete this item?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-icon btn-sm btn-light-danger" type="submit">
                                                        <i class="fa-solid fa-trash fs-6"></i>
                                                    </button>
                                                </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                {{-- CASE 2: Node has no children at all --}}
            @else
                <span class="text-muted fs-7">No child items.</span>
            @endif
        </div>
    </div>
</div>
