@props([
    'id' => 'iconPickerInput',
    'name',
    'class' => '',
    'buttonClass' => '',
    'value' => '',
])

<div class="icon-picker-wrapper position-relative" style="width:100%;">
    <div class="input-group">

        {{-- INPUT --}}
        <input type="text" id="{{ $id }}" name="{{ $name }}"
            class="form-control icon-picker-input {{ $class }}" placeholder="fa-solid fa-star"
            value="{{ $value }}" autocomplete="off">

        {{-- BUTTON --}}
        <button type="button" class="btn btn-outline icon-picker-toggle {{ $buttonClass }}"
            data-target="{{ $id }}">
            <i class="{{ $value ?: 'fa fa-icons' }}"></i>
        </button>
    </div>

    {{-- DROPDOWN --}}
    <div class="icon-picker-dropdown shadow-sm border rounded p-3 bg-white" data-dropdown="{{ $id }}"
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

        <input type="text" class="form-control form-control-sm mb-2 icon-search" placeholder="Search icons…">

        <div class="icon-grid d-grid gap-2" style="grid-template-columns: repeat(5, 1fr);"></div>
    </div>
</div>


@push('styles')
    <style>
        .icon-picker-wrapper .icon-box {
            border: 1px solid #eee;
            padding: 8px;
            cursor: pointer;
            border-radius: 6px;
            text-align: center;
            transition: all .15s;
            font-size: 16px;
        }

        .icon-picker-wrapper .icon-box:hover {
            background: #f5f5f5;
        }
    </style>
@endpush


@once
    @push('scripts')
        <script>
           
            let __faIconList = []; // cache icons so we don't reload JSON repeatedly
            let __faIconLoaded = false;

            // Load icon list once
            async function loadFaIcons() {
                if (__faIconLoaded) return __faIconList;

                const ICON_JSON_URL = "{{ asset('fontawesome/icons.json') }}";

                try {
                    const response = await fetch(ICON_JSON_URL);
                    const data = await response.json();

                    Object.keys(data).forEach(iconName => {
                        const icon = data[iconName];
                        if (!icon.free) return;

                        icon.free.forEach(style => {
                            let prefix =
                                style === "solid" ? "fa-solid" :
                                style === "regular" ? "fa-regular" :
                                style === "brands" ? "fa-brands" : "";

                            __faIconList.push(`${prefix} fa-${iconName}`);
                        });
                    });

                    __faIconLoaded = true;
                    return __faIconList;

                } catch (e) {
                    console.error("Error loading FontAwesome icons:", e);
                    return [];
                }
            }

            /* -----------------------------------------------------------
               INIT FUNCTION → Can run multiple times (repeaters)
            ----------------------------------------------------------- */
            window.initIconPicker = async function(wrapperElement = document) {

                await loadFaIcons(); // ensure icons loaded

                wrapperElement.querySelectorAll(".icon-picker-wrapper").forEach(wrapper => {

                    const input = wrapper.querySelector(".icon-picker-input");
                    const button = wrapper.querySelector(".icon-picker-toggle");
                    const dropdown = wrapper.querySelector(".icon-picker-dropdown");
                    const grid = wrapper.querySelector(".icon-grid");
                    const searchInput = wrapper.querySelector(".icon-search");

                    if (!input || !button || !dropdown || !grid) return;

                    /* RENDER ICONS */
                    function renderIcons(filter = "") {
                        grid.innerHTML = "";
                        __faIconList
                            .filter(icon => icon.toLowerCase().includes(filter.toLowerCase()))
                            .forEach(icon => {
                                const div = document.createElement("div");
                                div.className = "icon-box";
                                div.dataset.icon = icon;
                                div.innerHTML = `<i class="${icon}"></i>`;
                                div.onclick = () => {
                                    input.value = icon;
                                    button.innerHTML = `<i class="${icon}"></i>`;
                                    dropdown.style.display = "none";
                                };
                                grid.appendChild(div);
                            });
                    }

                    renderIcons();

                    /* BUTTON TOGGLE */
                    button.onclick = () => {
                        const isOpen = dropdown.style.display === "block";
                        document.querySelectorAll(".icon-picker-dropdown")
                            .forEach(d => d.style.display = "none");
                        dropdown.style.display = isOpen ? "none" : "block";
                    };

                    /* SEARCH */
                    searchInput.oninput = () => renderIcons(searchInput.value);

                    /* CLOSE ON OUTSIDE CLICK */
                    document.addEventListener("click", (e) => {
                        if (!wrapper.contains(e.target))
                            dropdown.style.display = "none";
                    });

                });
            };

            // AUTO-RUN ON PAGE LOAD
            document.addEventListener("DOMContentLoaded", () => {
                initIconPicker();
            });
        </script>
    @endpush
@endonce
