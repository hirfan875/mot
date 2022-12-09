<table style="width: 100%">
    <thead>
        <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Status</th>
            <th>Date/Time</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($contacts as $row)
        <tr>
            <td>{{ $row->id }}</td>
            <td>{{ $row->name }}</td>
            <td>{{ $row->email }}</td>
            <td>{{ $row->phone }}</td>
            <td>
                @if ( $row->status == 1 ) Viewed @else New @endif
            </td>
            <td>{{ $row->created_at }}</td>
        </tr>
        @endforeach
    </tbody>
</table>