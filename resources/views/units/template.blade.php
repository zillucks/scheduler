<table>
    <thead>
        <tr>
            <th>Unit Name</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @if ($units->count() > 0)
            @foreach ($units as $unit)
                <tr>
                    <td>{{ $unit->unit_name }}</td>
                    <td>{{ $unit->unit_status }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td>%STRING_unit_name%</td>
                <td>%BOOLEAN_unit_status%</td>
            </tr>
        @endif
    </tbody>
</table>