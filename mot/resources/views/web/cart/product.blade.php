<tr>
    <div class="loading-div d-none" id="loading-div-{{$cart_product->id}}">
        <div class="spinner-border text-danger" role="status">
            <span class="sr-only">{{__('Loading...')}}</span>
        </div>
    </div>
    <div class="loading-div d-none" id="loading-div-cart-inc-{{$cart_product->id}}">
        <div class="spinner-border text-danger" role="status">
            <span class="sr-only">{{__('Loading...')}}</span>
        </div>
    </div>
</tr>
<tr>
    <input type="hidden" name="stock" id="stock-{{$cart_product->product_id}}" value="{{$cart_product->product != null ? $cart_product->product->stock : 0}}">
    <td class="product-thumbnail">
        <a href="{{$cart_product->product->parent_id != null ? $cart_product->product->parent->getViewRoute() : $cart_product->product->getViewRoute()}}">
            <img loading="lazy" src="{{$cart_product->product->parent_id != null ? $cart_product->product->parent->product_thumbnail() : $cart_product->product->product_thumbnail()}}" width="130px" alt="{{$cart_product->title}}">
        </a>
    </td>
    <td class="product-name">
        <a href="{{$cart_product->product->parent_id != null ? $cart_product->product->parent->getViewRoute() : $cart_product->product->getViewRoute()}}">
            {{\Illuminate\Support\Str::limit(isset($cart_product->product->product_translates)? $cart_product->product->product_translates->title : $cart_product->title, 35)}} <span class="arbt">{{count( getAttributeWithOption($cart_product->product) ) > 0 ? getAttrbiuteString(getAttributeWithOption($cart_product->product)) : null}}</span>
            <span style="font-weight: bolder; color: red">{{ $cart_product->discounted_at != null ?  __('Get Free') : null}}</span>
        </a>
        <a href="{{route('shop', $cart_product->product->store->slug)}}">{{isset($cart_product->product->store->store_profile_translates)? $cart_product->product->store->store_profile_translates->name : $cart_product->product->store->name}}</a>
    </td>
    <td class="product-price" ><span class="amount"><span id="amount_{{$cart_product->product_id}}">{{currency_format($cart_product->unit_price)}}</span></span></td>
    <td class="product-quantity">
        <div class="btn-group">
            <button type="button" class="prev btn" id="pre-btn_{{$cart_product->product_id}}" onClick="return decrementValue({{$cart_product->product_id}})" {{$cart_product->quantity == 1 ? 'disabled' : null}}>-</button>
            <input type="text" class="show-number btn" id="quantity-{{$cart_product->product_id}}" value="{{$cart_product->quantity}}" readonly="" max="500" min="1" step="1">
            <button type="button" class="next btn" onClick="return incrementValue({{$cart_product->product_id}})">+</button>
        </div>
    </td>
    <td class="product-subtotal"><span id="sub_total_{{$cart_product->product_id}}"> {{currency_format($cart_product->sub_total)}}</span></td>
    <td class="product-remove"> <a href="javascript:;" onclick="return removeCartItem({{$cart_product->id}}, `{!! str_replace("''","",str_replace("'","",str_replace("/","", str_replace('"','',$cart_product->title) ))) !!}` ,'{{$cart_product->unit_price}}','{{$currentCurrency->code}}')"><i class="fa fa-trash-o" aria-hidden="true"></i></a></td>
</tr>
