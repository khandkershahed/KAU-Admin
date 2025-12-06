<x-admin-app-layout :title="'Admission Module'">

    <div class="card card-flash">
        <div class="card-header align-items-center">
            <h3 class="card-title fw-bold">Admission Module</h3>

            <div class="d-flex">

                {{-- SEARCH (simple, by title) --}}
                <div class="me-3 position-relative">
                    <input type="text" id="admissionSearchInput" class="form-control form-control-sm"
                        placeholder="Search admission items..." style="width:230px;height:36px;" />

                    <button type="button" id="clearAdmissionSearchBtn" class="btn btn-danger btn-sm position-absolute"
                        style="right:0;top:0;height:36px;display:none;">
                        <i class="fas fa-x"></i>
                    </button>
                </div>

                @can('create admission')
                    <a href="{{ route('admin.admission.create') }}" class="btn btn-primary btn-sm">
                        <i class="fa fa-plus me-2"></i> Add Item
                    </a>
                @endcan
            </div>
        </div>

        <div class="card-body">

            @if (session('success'))
                <div class="alert alert-success mb-3">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger mb-3">{{ session('error') }}</div>
            @endif

            {{-- ROOT ACCORDION (parent_id = null) --}}
            <div class="accordion node-sortable" id="admissionAccordion" data-parent-id="">
                @foreach ($roots as $root)
                    @include('admin.pages.admission.partials.node', ['node' => $root, 'level' => 0])
                @endforeach
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                const $search = $('#admissionSearchInput');
                const $clear = $('#clearAdmissionSearchBtn');

                function resetSearchState() {
                    $search.val('');
                    $clear.hide();

                    // Show all nodes and rows
                    $('.admission-node').show();
                    $('tbody.node-sortable tr').show();

                    // Collapse all, then open first root accordion for UX
                    $('.accordion-collapse').removeClass('show');
                    $('#admissionAccordion > .admission-node:first .accordion-collapse').addClass('show');
                }

                function markNodeAndAncestors($node, matchedNodes) {
                    if (!$node || !$node.length) return;

                    // This node + all ancestor nodes
                    $node.add($node.parents('.admission-node')).each(function() {
                        if (matchedNodes.indexOf(this) === -1) {
                            matchedNodes.push(this);
                        }
                    });
                }

                // ===========================
                // SEARCH
                // ===========================
                $search.on('input', function() {
                    const term = $(this).val().toLowerCase().trim();

                    if (!term.length) {
                        resetSearchState();
                        return;
                    }

                    $clear.show();

                    // Hide everything by default
                    $('.admission-node').hide();
                    $('tbody.node-sortable tr').hide();
                    $('.accordion-collapse').removeClass('show');

                    var matchedNodes = [];

                    // 1) Match by accordion header (title, slug, type badge)
                    $('.admission-node').each(function() {
                        const $node = $(this);
                        const headerText = $node.find('.accordion-header').text().toLowerCase();

                        if (headerText.indexOf(term) !== -1) {
                            markNodeAndAncestors($node, matchedNodes);
                        }
                    });

                    // 2) Match by table rows (leaf lists)
                    $('tbody.node-sortable tr').each(function() {
                        const $row = $(this);
                        const rowText = $row.text().toLowerCase();

                        if (rowText.indexOf(term) !== -1) {
                            $row.show();

                            const $node = $row.closest('.admission-node');
                            markNodeAndAncestors($node, matchedNodes);
                        }
                    });

                    // 3) Show all matched nodes & expand them
                    matchedNodes.forEach(function(nodeEl) {
                        const $node = $(nodeEl);
                        $node.show();
                        $node.children('.accordion-collapse').addClass('show');
                    });
                });

                // ===========================
                // CLEAR SEARCH BUTTON
                // ===========================
                $clear.on('click', function() {
                    resetSearchState();
                });
            });
        </script>
    @endpush
</x-admin-app-layout>
