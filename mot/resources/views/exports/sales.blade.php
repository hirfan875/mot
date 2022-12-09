<table style="width: 100%">
    <thead>
        <tr>
            <th>Order #</th>
            <th>User ID</th>
            <th>User Name</th>
            <th>Currency</th>
            <th>Total</th>
            <th>Ordered Currency Code</th>
            <th>Ordered total</th>
            <th>Status</th>
            <th>Order Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($orders as $row)
        <tr>
            <td>{{ $row->id }}</td>
            <td>@if(isset($row->customer))
                {{ $row->customer->id }}
                @endif
            </td>
            <td>@if(isset($row->customer))
                {{ $row->customer->name }}
                @endif
            </td>
            <td>{{__('TRY')}} </td>
            <td>{{ number_format($row->total, 2, ".", ",");  }} </td>
            <td class="text-center">{{__($row->currency->code)}}</td>
            <td>{{convertTryForexRate($row->total, $row->forex_rate, $row->base_forex_rate, $row->currency->code)}}</td>
            <td>{{ $row->payment_method }}</td>
            <td>
                 @if ( $row->status == 0 )
                    {{__('Uninitiated')}}
                @elseif ( $row->status == 1 )
                    {{__('Confirmed')}}
                @elseif ( $row->status == 2 )
                    {{__('Paid')}}
                @elseif ( $row->status == 3 )
                    {{__('Ready To Ship')}}
                @elseif ( $row->status == 4 )
                    {{__('Shipped')}}
                @elseif ( $row->status == 5 )
                    {{__('Delivered')}}
                @elseif ( $row->status == 6 )
                    {{__('Cancellation Requested')}}
                @elseif ( $row->status == 7 )
                    {{__('Cancelled')}}
                @elseif ( $row->status == 8 )
                    {{__('Return Requested')}}
                @elseif ( $row->status == 9 )
                    {{__('Delivery Failure')}}
                @endif
            </td>
            <td>{{ Carbon\Carbon::parse($row->created_at)->format('M j, Y g:i A') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>