<div class="settings-tabs card border-0 shadow-sm rounded-4 w-100">
    <div class="card-body px-4 py-3">
        <ul class="nav flex-column nav-pills" id="settings-tab" role="tablist" aria-orientation="vertical">

            @php
                $tabs = [
                    'generalInfo'  => 'General Info',
                    'footer'       => 'Footer & Legal',
                    'businessHours'=> 'Business Hours',
                    'seo'          => 'SEO & Analytics',
                    'socialLinks'  => 'Social Links',
                    // 'privacy'      => 'Privacy',
                    // 'terms'        => 'Terms',
                    'advance'      => 'Advanced',
                    'setting'      => 'System / SMTP',
                ];
            @endphp

            @foreach($tabs as $id => $label)
                <li class="nav-item mb-1 settings-nav-item" role="presentation">
                    <button
                        class="nav-link {{ $loop->first ? 'active' : '' }}"
                        id="{{ $id }}-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#{{ $id }}"
                        type="button"
                        role="tab">

                        {{ $label }}

                    </button>
                </li>
            @endforeach

        </ul>
    </div>
</div>

@push('styles')
<style>
    .settings-tabs {
        background: #ffffff;
        border-radius: 16px;
    }

    /* Default nav-item style */
    .settings-nav-item {
        border-bottom: 1px solid #eee;
        padding-bottom: 4px;
        padding-top: 4px;
    }

    .settings-nav-item:last-child {
        border-bottom: none;
    }

    /* Nav link design */
    .settings-tabs .nav-link {
        border-radius: 2px !important;
        font-size: 0.92rem;
        font-weight: 500;
        padding: 8px 12px;
        background-color: transparent;
        color: #1b2559;
        border: 1px solid transparent;
        transition: all .15s ease;
        text-align: left;
        width: 100%;
    }

    /* Hover effect */
    .settings-tabs .nav-link:hover {
        background-color: #f8f9fa;
        color: #0a1765;
    }

    /* ACTIVE is applied to nav-item (parent) */
    .settings-nav-item.active > .nav-link,
    .settings-tabs .nav-link.active {
        background-color: bisque !important;
        color: #0a1765 !important;
        border-color: #e0d2b9 !important;
        font-weight: 600;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const items = document.querySelectorAll('.settings-nav-item .nav-link');

        items.forEach(btn => {
            btn.addEventListener('click', function () {

                // Remove active from all nav-items
                document.querySelectorAll('.settings-nav-item').forEach(item => {
                    item.classList.remove('active');
                });

                // Add active to parent nav-item
                this.closest('.settings-nav-item').classList.add('active');
            });
        });

    });
</script>
@endpush
