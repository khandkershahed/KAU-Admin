{{-- @php
    $setting = $setting ?? null;
    $businessHours = [];

    if ($setting && $setting->business_hours) {
        $businessHours = is_array($setting->business_hours)
            ? $setting->business_hours
            : json_decode($setting->business_hours, true);
    }

    $weekDays = [
        'saturday'  => 'Saturday',
        'sunday'    => 'Sunday',
        'monday'    => 'Monday',
        'tuesday'   => 'Tuesday',
        'wednesday' => 'Wednesday',
        'thursday'  => 'Thursday',
        'friday'    => 'Friday',
    ];
@endphp --}}

@php
    $setting = $setting ?? null;
    $businessHours = $setting->business_hours ?? [];

    $weekDays = [
        'saturday' => 'Saturday',
        'sunday' => 'Sunday',
        'monday' => 'Monday',
        'tuesday' => 'Tuesday',
        'wednesday' => 'Wednesday',
        'thursday' => 'Thursday',
        'friday' => 'Friday',
    ];
@endphp

<div class="row mb-7">
    <div class="col-12">
        <h5 class="mb-7">Business Hours</h5>
        <small class="text-muted">Configure office hours exactly like in seeder.</small>
    </div>

    <div class="col-12">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Day</th>
                        <th style="width: 120px;">Start</th>
                        <th style="width: 120px;">End</th>
                        <th style="width: 80px;">Closed</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($weekDays as $key => $label)
                        @php
                            $dayData = $businessHours[$key] ?? [];
                            $start = old("business_hours.$key.start", $dayData['start'] ?? '');
                            $end = old("business_hours.$key.end", $dayData['end'] ?? '');
                            $closed = old("business_hours.$key.closed", $dayData['closed'] ?? false);
                        @endphp
                        <tr>
                            <td>{{ $label }}</td>
                            <td>
                                <input type="time" name="business_hours[{{ $key }}][start]"
                                    class="form-control" value="{{ $start }}"
                                    @if ($closed) disabled @endif>
                            </td>
                            <td>
                                <input type="time" name="business_hours[{{ $key }}][end]"
                                    class="form-control" value="{{ $end }}"
                                    @if ($closed) disabled @endif>
                            </td>
                            <td class="text-center">
                                <input type="hidden" name="business_hours[{{ $key }}][closed]" value="0">
                                <input type="checkbox" class="form-check-input toggle-closed"
                                    data-day="{{ $key }}" name="business_hours[{{ $key }}][closed]"
                                    value="1" {{ $closed ? 'checked' : '' }}>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.toggle-closed').forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    var day = this.getAttribute('data-day');
                    var row = this.closest('tr');
                    var startInput = row.querySelector('input[name="business_hours[' + day +
                        '][start]"]');
                    var endInput = row.querySelector('input[name="business_hours[' + day +
                        '][end]"]');

                    if (this.checked) {
                        startInput.setAttribute('disabled', 'disabled');
                        endInput.setAttribute('disabled', 'disabled');
                        startInput.value = '';
                        endInput.value = '';
                    } else {
                        startInput.removeAttribute('disabled');
                        endInput.removeAttribute('disabled');
                    }
                });
            });
        });
    </script>
@endpush
