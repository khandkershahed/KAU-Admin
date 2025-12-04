@foreach ($items as $item)
    @php
        $hasSub = !empty($item['subMenu']);
        $active = isset($item['routes']) ? Route::is(...$item['routes']) : false;

        // Permission check for current item
        $canView = true;
        if (isset($item['permission'])) {
            $canView = auth()->user()->can($item['permission']);
        }

        // Recursively check if any child submenu is visible
        if ($hasSub) {
            $hasVisibleChild = false;
            foreach ($item['subMenu'] as $child) {
                if (!isset($child['permission']) || auth()->user()->can($child['permission'])) {
                    $hasVisibleChild = true;
                    break;
                }
            }

            // If no child is visible, hide this parent menu
            if (!$hasVisibleChild) {
                continue;
            }
        }

        // Hide normal item if permission denied
        if (!$canView) {
            continue;
        }
    @endphp


    @if (isset($item['type']) && $item['type'] === 'heading')
        <div class="menu-item">
            <div class="menu-content pt-4 pb-1">
                <span class="menu-section text-muted text-uppercase fs-8 ls-1">
                    {{ $item['title'] }}
                </span>
            </div>
        </div>
        @continue
    @endif


    @if ($hasSub)
        <div data-kt-menu-trigger="click"
            class="menu-item menu-accordion {{ $active ? 'here show' : '' }}">

            <span class="menu-link">
                @if (isset($item['icon']))
                    <span class="menu-icon">
                        <i class="{{ $item['icon'] }}"></i>
                    </span>
                @endif

                <span class="menu-title">{{ $item['title'] }}</span>
                <span class="menu-arrow"></span>
            </span>

            <div class="menu-sub menu-sub-accordion menu-active-bg">
                @include('admin.layouts.menu-recursive', ['items' => $item['subMenu']])
            </div>

        </div>
    @else
        <div class="menu-item {{ $active ? 'active' : '' }}">
            <a class="menu-link {{ $active ? 'active' : '' }}"
                href="{{ isset($item['route']) ? route($item['route']) : '#' }}">

                @if (isset($item['icon']))
                    <span class="menu-icon">
                        <i class="{{ $item['icon'] }}"></i>
                    </span>
                @else
                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                @endif

                <span class="menu-title">{{ $item['title'] }}</span>
            </a>
        </div>
    @endif
@endforeach
