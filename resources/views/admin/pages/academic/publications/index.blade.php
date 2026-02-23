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
                    class="btn btn-sm btn-dark">
                    <i class="fa-solid fa-arrow-left me-2"></i> Back
                </a>
            </div>
        </div>

        <div class="card-body">

            @if ($publications->isEmpty())
                <div class="text-muted">No publications found.</div>
            @else
                <div class="table-responsive p-0">
                    <table class="table datatable table-striped mb-0 align-middle" style="width:100%; table-layout: fixed;">
                        <thead class="text-center">
                            <tr>
                                <th class="text-center" style="width:60px;">SL</th>
                                <th class="text-center" style="width:65%;">Title</th>
                                <th class="text-center" style="width:130px;">Type</th>
                                {{-- <th class="text-center" style="width:220px;">Journal/Conference</th> --}}
                                <th class="text-center" style="width:90px;">Year</th>
                                <th class="text-center" style="width:120px;">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($publications as $p)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>

                                    <td style="white-space: normal; word-break: break-word;">
                                        <div class="fw-semibold">{{ $p->title }}</div>
                                        @if ($p->url)
                                            <div class="small">
                                                <a href="{{ $p->url }}" target="_blank" rel="noopener noreferrer">
                                                    {{ $p->url }}
                                                </a>
                                            </div>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        {{ $p->type ? ucfirst(str_replace('_', ' ', $p->type)) : '-' }}
                                    </td>

                                    {{-- <td style="white-space: normal; word-break: break-word;">
                                        {{ $p->journal_or_conference_name ?? '-' }}
                                    </td> --}}

                                    <td class="text-center">{{ $p->year ?? '-' }}</td>

                                    <td class="text-center">
                                        <a href="{{ route('admin.academic.publications.edit', $p->id) }}"
                                            class="me-1">
                                            <i class="fa-solid fa-pen-to-square text-primary"></i>
                                        </a>

                                        <a href="{{ route('admin.academic.publications.destroy', $p->id) }}"
                                            class="delete">
                                            <i class="fa-solid fa-trash text-danger"></i>
                                        </a>
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
