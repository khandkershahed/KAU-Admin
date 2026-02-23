<div class="table-responsive p-0">
    <table class="table table-striped mb-0">
        <thead>
            <tr>
                <th width="10%">Type</th>
                <th width="75%">Title</th>
                <th width="10%">Year</th>
                <th width="5%">Skip</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($allRows as $row)
                <tr>
                    <td>
                        <input class="form-control form-control-sm js-type" value="{{ $row['type'] }}">
                    </td>
                    <td>
                        <textarea class="form-control form-control-sm js-title" rows="3">{{ $row['title'] }}</textarea>
                        {{-- <input class="form-control form-control-sm js-title" value="{{ $row['title'] }}"> --}}
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm js-year" value="{{ $row['year'] }}">
                    </td>
                    <td class="text-center">
                        <input type="checkbox" class="form-check-input js-skip">
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
