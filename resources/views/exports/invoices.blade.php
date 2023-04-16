<table border="1">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
        </tr>
    </thead>
    <tbody>
        @foreach($user as $u)
        <tr>
            <td style="background-color:aquamarine">{{ $u->name }}</td>
            <td>{{ $u->email }}</td>
        </tr>
        @endforeach
    </tbody>
</table>