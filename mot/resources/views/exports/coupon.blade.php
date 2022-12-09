<table style="width: 100%">
    <thead>
        <tr>
            <th class="text-center">{{ __('Order #') }}</th>
            <th class="text-center">{{ __('Store Order #') }}</th>
            <th class="text-left">{{ __('Customer') }}</th>
            <th class="text-left">{{ __('Coupon Code') }}</th>
            <th class="text-left">{{ __('Discount') }}</th>
            <th class="text-center">{{ __('Order Total') }}</th>
            <th class="text-center">{{ __('Order Date') }}</th>
            <th class="text-center">{{ __('Status') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($orders as $row)
        <tr>
            <td class="text-center">{{ (isset($row->order))? $row->order->order_number:"" }}</td>
            <td class="text-center">{{ $row->order_number }}</td>
            <td class="text-left">{{ $row->order->customer->name }}</td>
            <td class="text-left">{{ $row->order->coupon->coupon_code }}</td>
            <td class="text-left">{{ $row->order->coupon->discount }} {{ $row->order->coupon->type == 'percentage' ? '%' : 'Fixed' }}</td>
            <td class="text-center">{{__($row->order->currency->code)}}&nbsp;{{convertTryForexRate($row->total, $row->order->forex_rate, $row->order->base_forex_rate, $row->order->currency->code)}} </td>
            <td class="text-center">{{ $row->order->order_date }}</td>
            <td class="text-center">{{ __($row->getStatus($row->status)) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>