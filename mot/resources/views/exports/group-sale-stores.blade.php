<table style="width: 100%">
    <thead>
        <tr>
            <th class="text-center" >{{__('Seller')}}</th>
            <th class="text-center" >{{__('Total Order')}}</th>
            <th class="text-center" >{{__('Currency')}}</th>
            <th class="text-center" >{{__('SubTotal Sales')}}</th>
            <th class="text-center" >{{__('Delivery Fee')}}</th>
            <th class="text-center" >{{__('Total Sales')}}</th>
            <th class="text-center" >{{__('Date')}} </th>
        </tr>
    </thead>
    
    <tbody>
        @foreach ($orders as $row)
        <tr>
            <td class="text-left">{{$row->seller->name}}</td>
            <td class="text-center">{{ $row->countTotal }}</td>
            <td class="text-center">{{__('TRY')}}</td>
            <td class="text-center">{{ number_format($row->amountTotal, 2, ".", ","); }}</td>
            <td class="text-center">{{ number_format($row->deliveryFee, 2, ".", ","); }}</td> 
            <td class="text-center">{{ number_format($row->amountTotal + $row->deliveryFee, 2, ".", ","); }}</td> 
            <td class="text-center">{{ $row->date }}</td>
        </tr>
        @endforeach
    </tbody>
</table>