<table>
    <thead>
        <tr>
            <th>Organization Name</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @if ($organizations->count() > 0)
            @foreach ($organizations as $organization)
                <tr>
                    <td>{{ $organization->organization_name }}</td>
                    <td>{{ $organization->organization_status }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td>%STRING_organizations_name%</td>
                <td>%BOOLEAN_organizations_status%</td>
            </tr>
        @endif
    </tbody>
</table>