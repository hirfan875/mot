<div class="modal-header">
  <h5 class="modal-title">{{ __('Order') }} # {{ $order->order_number }}</h5>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
<div class="modal-body">
  <div class="table-responsiev">
    <table class="table table-sm">
      <thead>
        <tr>
          <th width="15%" class="border-top-0"></th>
          <th width="40%" class="border-top-0">{{__('Product')}}</th>
          <th width="15%" class="border-top-0">{{__('Quantity')}}</th>
          <th width="15%" class="border-top-0">{{__('Unit Price')}}</th>
          <th width="15%" class="text-right border-top-0">{{__('Total')}}</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($order->order_items as $item)
        @if(isset($item->product))
        <tr>
          <td><img loading="lazy" src="{{ $item->product->product_listing()}}" /></td>
          <td class="align-middle">
              <span><h5>{{isset($item->product->store->store_profile_translates)? $item->product->store->store_profile_translates->name : $item->product->store->name}}</h5></span>
              <hr>
              @if($item->product->parent_id != null)
              {{isset($item->product->parent->product_translates)? $item->product->parent->product_translates->title : $item->product->parent->title}}
              <br>
              <span class="admin-order-detail-arbt">{{ count(getAttributeWithOption($item->product)) > 0 ? getAttrbiuteString(getAttributeWithOption($item->product)) : null}}</span>
              <br/>
              <span style="font-weight: bolder; color: red">{{ $item->discounted_at != null ?  __('Get Free') : null}}</span>

              SKU: {{$item->product->sku}}
            @else
              {{isset($item->product->product_translates)? $item->product->product_translates->title : $item->product->title}}
              <br/>
              <span style="font-weight: bolder; color: red">{{ $item->discounted_at != null ?  __('Get Free') : null}}</span>

              SKU: {{$item->product->sku}}
            @endif
          </td>
          <td class="align-middle">{{ $item->quantity }}</td>
          <td class="align-middle">{{__($order->currency->code)}}&nbsp;{{convertTryForexRate($item->unit_price, $order->forex_rate, $order->base_forex_rate, $order->currency->code)}}</td>
          <td class="text-right align-middle"> {{__($order->currency->code)}}&nbsp;{{convertTryForexRate($item->total, $order->forex_rate, $order->base_forex_rate, $order->currency->code)}}</td>
        </tr>
        @endif
        @endforeach
      </tbody>
    </table>
  </div>
</div>