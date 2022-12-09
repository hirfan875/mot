<div class="tab-pane fade active show" id="mycancelation" role="tabpanel" aria-labelledby="user-requests-tab">

    <div class="table-content table-responsive mb-45 tbUser">
        <h2 class="mt-4 mb-3">{{__('Cancelled Order')}}</h2>
        @if($cancelledOrders)
            <table>
                <thead>
                <tr>
                    <th class="product-thumbnail">{{__('Image')}}</th>
                    <th class="product-name">{{__('Product')}}</th>
                    <th class="product-price">{{__('Price')}}</th>
                    <th class="product-quantity">{{__('Stock Status')}}</th>
                    <th class="product-remove">{{__('Remove')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($cancelledOrders as $order)
                    @foreach($order->store_orders as $storeOrder)
                        @foreach($storeOrder->order_items as $orderItem)
                    <tr>
                        <td class="product-thumbnail">
                        <a href="{{$orderItem->product->getViewRoute()}}"><img src="{{$orderItem->product->product_listing()}}" alt="{{$orderItem->product->title}}"/></a>
                        </td>
                        <td class="product-name"><a href="{{$orderItem->product->getViewRoute()}}">{{$orderItem->product->title}} <span>{{$orderItem->product->store->title}}</span></a></td>
                        <td class="product-price"><span class="amount">{{currency_format($orderItem->product->promo_price)}}</span></td>
                        <td class="product-quantity">
                            <span class="badge badge-pill badge-danger p-2">{{ $order->getStatus() }}</span>
                        </td>
                        <td class="product-remove"> <a href="#" class="btnDelete"><i class="fa fa-times" aria-hidden="true"></i></a></td>
                    </tr>
                        @endforeach
                    @endforeach
                @endforeach

                </tbody>
            </table>
        @else
            {{__('You dont have any cancellation')}}
        @endif
    </div>
</div>
