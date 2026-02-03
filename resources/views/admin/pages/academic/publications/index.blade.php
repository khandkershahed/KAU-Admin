<x-admin-app-layout :title="'Publications'">
    <div class="card shadow-sm">
        <div class="card-header d-flex align-items-center justify-content-between" style="min-height: 100px;">
            @php
                $section = $member->section;
                $site = $section?->site;
                $dept = $section?->department;
            @endphp
            {{-- @dd($member) --}}
            <div>
                <h3 class="fw-bold mb-2">{{ $member->name }}'s <small> Publications</small></h3>
                <div class="h5 text-muted">
                    Department: <b>{{ $dept->title }}</b>; Faculty :
                    <b>{{ $site->name }}</b>
                </div>
            </div>

            <div class="d-flex align-items-center">
                <a href="{{ route('admin.academic.publications.create', $member->id) }}"
                    class="btn btn-sm btn-primary me-2">
                    <i class="fa fa-plus me-2"></i>Bulk Add
                </a>
                <a href="{{ route('admin.academic.staff.index', ['site_id' => $site->id, 'department_id' => $dept->id]) }}"
                    class="btn btn-sm btn-secondary">
                    <i class="fa-solid fa-arrow-left me-2"></i> Back
                </a>
            </div>
        </div>

        <div class="card-body">
            @if ($publications->isEmpty())
                <div class="text-muted">No publications found.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-row-bordered align-middle gy-3">
                        <thead>
                            <tr class="fw-bold">
                                <th style="width:70px;">Pos</th>
                                <th>Title</th>
                                <th style="width:150px;">Type</th>
                                <th style="width:200px;">Journal/Conference</th>
                                <th style="width:120px;">Year</th>
                                <th style="width:140px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($publications as $p)
                                <tr>
                                    <td>{{ $p->position }}</td>
                                    <td>
                                        <div class="fw-semibold">{{ $p->title }}</div>
                                        @if ($p->url)
                                            <div class="small"><a href="{{ $p->url }}"
                                                    target="_blank">{{ $p->url }}</a></div>
                                        @endif
                                    </td>
                                    <td>{{ $p->type ? ucfirst(str_replace('_', ' ', $p->type)) : '-' }}</td>
                                    <td>{{ $p->journal_or_conference_name }}</td>
                                    <td>{{ $p->year }}</td>
                                    <td>
                                        <a href="{{ route('admin.academic.publications.edit', $p->id) }}"
                                            class="btn btn-sm btn-light-success me-2"><i class="fa fa-edit"></i></a>
                                        <a href="{{ route('admin.academic.publications.destroy', $p->id) }}"
                                            class="btn btn-sm btn-light-danger delete"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-admin-app-layout>
