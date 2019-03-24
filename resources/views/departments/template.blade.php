<table>
    <thead>
        <tr>
            <th>Department Name</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @if ($departments->count() > 0) 
            @foreach ($departments as $department)
                <tr>
                    <td>{{ $department->department_name }}</td>
                    <td>{{ $department->department_status }}</td>
                </tr>
            @endforeach 
        @else
            <tr>
                <td>%STRING_department_name%</td>
                <td>%BOOLEAN_department_status%</td>
            </tr>
        @endif
    </tbody>
</table>