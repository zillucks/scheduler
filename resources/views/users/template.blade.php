<table>
    <thead>
        <tr>
            <th>Username</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Password</th>
            <th>Organization</th>
            <th>Department</th>
            <th>Site</th>
            <th>Role ID</th>
            <th>Status</th>
            <th>User LDAP</th>
        </tr>
    </thead>
    <tbody>
        @if ($users->count() > 0)
            @foreach ($users as $user)
            <tr>
                <td>{{ $user->username }}</td>
                <td>{{ isset($user->identity) ? $user->identity->full_name : '' }}</td>
                <td>{{ isset($user->identity) ? $user->identity->email : '' }}</td>
                <td></td>
                <td>{{ isset($user->identity->organization) ? $user->identity->organization->slug : '' }}</td>
                <td>{{ isset($user->identity->department) ? $user->identity->department->slug : '' }}</td>
                <td>{{ isset($user->identity->site) ? $user->identity->site->slug : '' }}</td>
                <td>{{ $user->roles[0]->slug }}</td>
                <td>{{ $user->identity->identity_status }}</td>
                <td>{{ isset($user->identity) ? $user->identity->user_ldap : '' }}</td>
            </tr>
            @endforeach
        @else
            <tr>
                <td>%username%</td>
                <td>%full_name%</td>
                <td>%email%</td>
                <td>%password%</td>
                <td>%organization%</td>
                <td>%department%</td>
                <td>%site%</td>
                <td>%role%</td>
                <td>%status%</td>
                <td>%ldap%</td>
            </tr>
        @endif
    </tbody>
</table>