<table>
    <thead>
        <tr>
            <th>Site Name</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @if ($sites->count() > 0)
            @foreach ($sites as $site)
                <tr>
                    <td>{{ $site->site_name }}</td>
                    <td>{{ $site->site_status }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td>%STRING_site_name%</td>
                <td>%BOOLEAN_site_status%</td>
            </tr>
        @endif
    </tbody>
</table>