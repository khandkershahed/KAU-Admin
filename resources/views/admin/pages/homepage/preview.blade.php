@php
    // $sections, $banners, $vc, $explore, $faculty, $glance, $about
@endphp

<div class="homepage-preview">

    @foreach ($sections as $section)
        @if (! $section->is_active)
            @continue
        @endif

        @switch($section->section_key)

            {{-- ================= BANNER SLIDER ================= --}}
            @case('banner')
                <section class="py-6 border-bottom">
                    @if($banners->count())
                        <div class="row g-4 align-items-center">
                            @php $first = $banners->first(); @endphp
                            <div class="col-md-6">
                                <h2 class="fw-bold fs-2 mb-2">{{ $first->title }}</h2>
                                <p class="text-muted mb-3">{{ $first->subtitle }}</p>
                                @if($first->button_text)
                                    <a href="{{ $first->button_url ?? '#' }}" class="btn btn-sm btn-primary">
                                        {{ $first->button_text }}
                                    </a>
                                @endif
                            </div>
                            <div class="col-md-6 text-end">
                                @if($first->image_path)
                                    <img src="{{ asset('storage/'.$first->image_path) }}"
                                         alt="Banner"
                                         class="img-fluid rounded">
                                @endif
                            </div>
                        </div>
                    @else
                        <p class="text-muted mb-0">No banner slides configured yet.</p>
                    @endif
                </section>
                @break

            {{-- ================= VC MESSAGE ================= --}}
            @case('vc')
                @if($vc)
                    <section class="py-6 border-bottom">
                        <h3 class="fw-bold mb-3">{{ $vc->message_title }}</h3>
                        <div class="d-flex flex-column flex-md-row align-items-start gap-4">
                            @if($vc->vc_image)
                                <div class="flex-shrink-0">
                                    <div class="symbol symbol-75px">
                                        <img src="{{ asset('storage/'.$vc->vc_image) }}" alt="VC">
                                    </div>
                                </div>
                            @endif
                            <div>
                                <p class="text-muted mb-3">{!! nl2br(e($vc->message_text)) !!}</p>
                                <div class="fw-semibold">{{ $vc->vc_name }}</div>
                                <div class="text-muted">{{ $vc->vc_designation }}</div>
                                @if($vc->button_name && $vc->button_url)
                                    <div class="mt-3">
                                        <a href="{{ $vc->button_url }}" class="btn btn-sm btn-outline-primary">
                                            {{ $vc->button_name }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </section>
                @endif
                @break

            {{-- ================= EXPLORE KAU ================= --}}
            @case('explore')
                @if($explore)
                    <section class="py-6 border-bottom">
                        <h3 class="fw-bold mb-2">{{ $explore->section_title }}</h3>
                        <p class="text-muted mb-4"></p>

                        <div class="row g-4">
                            @forelse($explore->items as $item)
                                <div class="col-md-4">
                                    <div class="border rounded p-3 h-100 text-center">
                                        @if($item->icon)
                                            <i class="{{ $item->icon }} fs-2 mb-2 d-block"></i>
                                        @endif
                                        <div class="fw-semibold">{{ $item->title }}</div>
                                        @if($item->url)
                                            <div class="text-muted fs-8">{{ $item->url }}</div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted mb-0">No explore boxes yet.</p>
                            @endforelse
                        </div>
                    </section>
                @endif
                @break

            {{-- ================= FACULTIES ================= --}}
            @case('faculty')
                @if($faculty)
                    <section class="py-6 border-bottom">
                        <h3 class="fw-bold mb-2">{{ $faculty->section_title }}</h3>
                        <p class="text-muted mb-0">{{ $faculty->section_subtitle }}</p>
                        <div class="text-muted fs-8 mt-2">
                            (Faculty cards will be loaded from Faculties table on frontend.)
                        </div>
                    </section>
                @endif
                @break

            {{-- ================= KAU AT A GLANCE ================= --}}
            @case('glance')
                @if($glance)
                    <section class="py-6 border-bottom">
                        <h3 class="fw-bold mb-2">{{ $glance->section_title }}</h3>
                        <p class="text-muted mb-4">{{ $glance->section_subtitle }}</p>

                        <div class="row g-4">
                            @forelse($glance->items as $item)
                                <div class="col-md-3">
                                    <div class="border rounded p-3 text-center h-100">
                                        @if($item->icon)
                                            <i class="{{ $item->icon }} fs-2 mb-2 d-block"></i>
                                        @endif
                                        <div class="fw-bold fs-3">{{ $item->number }}</div>
                                        <div class="text-muted fs-8">{{ $item->title }}</div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted mb-0">No glance stats yet.</p>
                            @endforelse
                        </div>
                    </section>
                @endif
                @break

            {{-- ================= ABOUT ================= --}}
            @case('about')
                @if($about)
                    @php $aboutImages = $about->images_array ?? []; @endphp
                    <section class="py-6 border-bottom">
                        @if($about->badge)
                            <span class="badge badge-light-primary mb-2">{{ $about->badge }}</span>
                        @endif
                        <h3 class="fw-bold mb-1">{{ $about->title }}</h3>
                        <h5 class="text-muted mb-3">{{ $about->subtitle }}</h5>
                        <p class="text-gray-700 mb-4">{!! nl2br(e($about->description)) !!}</p>

                        <div class="row g-3 mb-3">
                            @foreach($aboutImages as $img)
                                @if($img)
                                    <div class="col-md-4">
                                        <div class="border rounded overflow-hidden">
                                            <img src="{{ asset('storage/'.$img) }}" class="w-100" alt="">
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        @if($about->experience_badge || $about->experience_title)
                            <span class="badge badge-light-success">
                                {{ $about->experience_badge ?: $about->experience_title }}
                            </span>
                        @endif
                    </section>
                @endif
                @break

        @endswitch
    @endforeach
</div>
