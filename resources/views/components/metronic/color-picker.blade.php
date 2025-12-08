@props([
    'id' => 'colorPickerInput',
    'name',
    'value' => '#000000',
    'class' => '',
    'buttonClass' => '',
])

<div class="color-picker-wrapper position-relative" style="width: 100%;">
    <div class="input-group">

        {{-- INPUT --}}
        <input type="text" id="{{ $id }}" name="{{ $name }}"
            class="form-control color-picker-input {{ $class }}" value="{{ $value }}" autocomplete="off"
            placeholder="#000000">

        {{-- COLOR BUTTON --}}
        <button type="button"
            class="btn btn-outline btn-outline-secondary btn-active-secondary color-picker-toggle {{ $buttonClass }}"
            data-target="{{ $id }}" style="background-color: {{ $value }};">
        </button>

    </div>

    {{-- DROPDOWN --}}
    <div class="color-picker-dropdown shadow-sm border rounded p-3 bg-white" data-dropdown="{{ $id }}"
        style="
            position:absolute;
            top:45px;
            left:0;
            width:260px;
            max-height:300px;
            overflow-y:auto;
            display:none;
            z-index:1000;
        ">

        <input type="text" class="form-control form-control-sm mb-2 color-search" placeholder="Search HEX…">

        <div class="color-grid d-grid gap-2 mb-3" style="grid-template-columns: repeat(6, 1fr);"></div>

        <div class="d-flex justify-content-between">
            <input type="color" class="form-control form-control-color flex-grow-1 color-native"
                value="{{ $value }}">
        </div>

    </div>
</div>

@push('styles')
    <style>
        .color-box {
            width: 30px;
            height: 30px;
            border-radius: 5px;
            cursor: pointer;
            border: 1px solid #ddd;
        }

        .color-box:hover {
            border: 2px solid #222;
        }
    </style>
@endpush

@once
    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function() {

                const PRESET_COLORS = [
                    "#0a5db6", "#1d4ed8", "#2563eb", "#3b82f6", "#1e40af",
                    "#10b981", "#059669", "#047857", "#6366f1", "#a855f7",
                    "#d946ef", "#ec4899", "#f43f5e", "#ef4444", "#f97316",
                    "#fb923c", "#fbbf24", "#eab308", "#22c55e", "#84cc16",
                    "#14b8a6", "#06b6d4", "#0ea5e9", "#3b82f6", "#64748b"
                ];

                document.querySelectorAll(".color-picker-wrapper").forEach(wrapper => {

                    const input = wrapper.querySelector(".color-picker-input");
                    const button = wrapper.querySelector(".color-picker-toggle");
                    const dropdown = wrapper.querySelector(".color-picker-dropdown");
                    const grid = wrapper.querySelector(".color-grid");
                    const searchInput = wrapper.querySelector(".color-search");
                    const nativeColor = wrapper.querySelector(".color-native");

                    /** Render Preset Colors */
                    function renderColors(filter = "") {
                        grid.innerHTML = "";
                        PRESET_COLORS
                            .filter(c => c.toLowerCase().includes(filter.toLowerCase()))
                            .forEach(color => {
                                const box = document.createElement("div");
                                box.className = "color-box";
                                box.style.backgroundColor = color;
                                box.dataset.color = color;

                                box.onclick = () => {
                                    input.value = color;
                                    button.style.backgroundColor = color;
                                    dropdown.style.display = "none";
                                };

                                grid.appendChild(box);
                            });
                    }

                    renderColors();

                    /** Toggle dropdown */
                    button.onclick = () => {
                        const isOpen = dropdown.style.display === "block";
                        document.querySelectorAll(".color-picker-dropdown")
                            .forEach(d => d.style.display = "none");
                        dropdown.style.display = isOpen ? "none" : "block";
                    };

                    /** Manual HEX search */
                    searchInput.oninput = () => renderColors(searchInput.value);

                    /** Native color input */
                    nativeColor.oninput = () => {
                        input.value = nativeColor.value;
                        button.style.backgroundColor = nativeColor.value;
                    };

                    /** Sync input change */
                    input.oninput = () => {
                        button.style.backgroundColor = input.value;
                    };

                    /** Close when clicking outside */
                    document.addEventListener("click", function(e) {
                        if (!wrapper.contains(e.target))
                            dropdown.style.display = "none";
                    });
                });

            });
        </script>
    @endpush
@endonce
