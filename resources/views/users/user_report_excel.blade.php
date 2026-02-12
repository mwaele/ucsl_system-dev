<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Role</th>
            <th>Station</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
            <tr>
                <td> {{ $loop->iteration }}.</td>
                <td> {{ $user->name }} </td>
                <td> {{ $user->phone_number }} </td>
                <td> {{ $user->email }} </td>
                <td> {{ $user->role }} </td>
                <td> {{ $user->office?->name }} </td>
                <td> {{ $user->status }} </td>
            </tr>
        @endforeach
    </tbody>
</table>