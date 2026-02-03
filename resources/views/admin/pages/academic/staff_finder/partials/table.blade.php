<div class="table-responsive">
    <table class="table table-row-bordered border align-middle gy-3">
        <thead>
            <tr class="fw-bold text-muted">
                <th>Name</th>
                <th>Email</th>
                <th>Phone & Mobile</th>
                <th>Faculty</th>
                <th>Department</th>
                <th>Status</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($members as $member)
                @php
                    $section = $member->section;
                    $site = $section?->site;
                    $dept = $section?->department;

                    // Prefer page-based routes (if you implemented them), otherwise fallback to the main staff page
                    // $hasEditPage = \Illuminate\Support\Facades\Route::has('admin.academic.staff-members.edit');
                    // $hasPubCreatePage = \Illuminate\Support\Facades\Route::has('admin.academic.publications.create');

                    // $editUrl = $hasEditPage
                    //     ? route('admin.academic.staff-members.edit', $member->id)
                    //     : route('admin.academic.staff.index', ['site_id' => $site?->id, 'open_member' => $member->id]);

                    // $pubUrl = $hasPubCreatePage
                    //     ? route('admin.academic.publications.index', $member->id)
                    //     : route('admin.academic.staff.index', [
                    //         'site_id' => $site?->id,
                    //         'open_publications' => $member->id,
                    //     ]);
                @endphp

                <tr>
                    <td class="fw-semibold">{{ $member->name }}</td>
                    <td>{{ $member->email }}</td>
                    <td>
                        <p>{{ $member->phone }}</p>
                        <p>{{ $member->mobile }}</p>
                    </td>
                    <td>{{ $site?->name }} {{ $site?->short_name ? '(' . $site->short_name . ')' : '' }}</td>
                    <td>{{ $dept?->title }}</td>
                    <td>
                        {{-- 'published','draft','archived' --}}
                        @if ($member->status === 'published')
                            <span class="badge badge-light-success">Published</span>
                        @elseif ($member->status === 'draft')
                            <span class="badge badge-light-warning">Draft</span>
                        @elseif ($member->status === 'archived')
                            <span class="badge badge-light-danger">Archived</span>
                        @endif
                    </td>
                    <td class="text-end">
                        @can('edit academic staff')
                            <a href="{{ route('admin.academic.staff-members.edit', $member->id) }}"
                                class="btn btn-light-success btn-sm me-2"><i class="fa fa-edit"></i></a>
                        @endcan

                        @can('delete academic staff')
                            <a href="{{ route('admin.academic.staff-members.destroy', $member->id) }}"
                                class="btn btn-light-danger btn-sm delete">
                                <i class="fa fa-trash"></i>
                            </a>
                        @endcan
                        <a href="{{ route('admin.academic.publications.index', $member->id) }}"
                            class="btn btn-sm btn-light-primary"><i class="fa fa-book me-2"></i>Publications</a>

                        {{-- <a href="{{ $editUrl }}" class="btn btn-sm btn-light-primary">
                            <i class="fa-solid fa-pen-to-square fs-6"></i>
                        </a>
                        <a href="{{ $pubUrl }}" class="btn btn-sm btn-light-info">
                            Publications
                        </a> --}}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9">
                        <div class="text-center text-muted py-6">
                            No staff members found.
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if ($members->hasPages())
    <div class="mt-4">
        {!! $members->links() !!}
    </div>
@endif
