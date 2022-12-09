<table style="width: 100%">
    <thead>
        <tr>
            <th>Order #</th>
            <th>User ID</th>
            <th>User Name</th>
            <th>Total</th>
            <th>Currency</th>
            <th>Payment Method</th>
            <th>Status</th>
            <th>Order Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($orders as $row)
        <tr>
            <td>{{ $row->id }}</td>
            <td>{{ $row->customer->id }}</td>
            <td>{{ $row->customer->name }}</td>
            <td>{{ $row->total }}</td>
            <td>{{ $currencies[$row->country_id] }}</td>
            <td>{{ $row->payment_method }}</td>
            <td>
                @if ( $row->status == 2 )
                Completed
                @elseif ( $row->status == 3 )
                Cancelled
                @else
                In Process
                @endif
            </td>
            <td>{{ Carbon\Carbon::parse($row->created_at)->format('M j, Y g:i A') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>