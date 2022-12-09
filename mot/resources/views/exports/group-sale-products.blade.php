<table style="width: 100%">
    <thead>
        <tr>
            <th class="text-center">{{__('Title')}}</th>
            <th class="text-center">{{__('Total Order')}}</th>
            <th class="text-center" >{{__('Currency')}}</th>
            <th class="text-center">{{__('Total Unit Price')}}</th>
            <th class="text-center">{{__('quantity')}}</th>
            <th class="text-center">{{__('Date')}} </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($orders as $row)
        @if(isset($row['product']['title']))
        <tr>
            <td class="text-center">{{ $row['product']['title'] }}</td> 
            <td class="text-center">{{ $row['countTotal'] }}</td>
            <td class="text-center">{{__('TRY')}}</td>
            <td class="text-center">{{ number_format($row['unit_price'], 2, ".", ","); }}</td>
            <td class="text-center">{{ $row['quantity'] }}</td> 
            <td class="text-center">{{ $row['date'] }}</td>
        </tr>
        @endif
        @endforeach
    </tbody>
</table>