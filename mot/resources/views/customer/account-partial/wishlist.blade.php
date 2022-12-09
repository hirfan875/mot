<div class="tab-pane fade active show" id="wishlist-table" role="tabpanel" aria-labelledby="user-requests-tab">
    <div class="table-content table-responsive mb-45  tbUser">
        @if(count($wishlists) > 0)
        <table>
            <thead>
                <tr>
                    <th class="product-thumbnail">{{__('Image')}}</th>
                    <th class="product-name">{{__('Product')}}</th>
                    <th class="product-price">{{__('Price')}}</th>
                    <th class="product-quantity">{{__('Stock')}}</th>
                    <th class="product-remove">{{__('Remove')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($wishlists as $wishlist)
                    @if(isset($wishlist->product->id))
                        <tr>
                            <div class="loading-div d-none" id="loading-div-{{$wishlist->product->id}}">
                                <div class="spinner-border text-danger" role="status">
                                    <span class="sr-only">{{__('Loading...')}}</span>
                                </div>
                            </div>
                            <td class="product-thumbnail">
                                <a href="{{$wishlist->product->getViewRoute()}}"><img src="{{$wishlist->product->product_listing()}}" alt="{{$wishlist->product->title}}"></a>
                            </td>
                            <td class="product-name">
                                <a href="{{$wishlist->product->getViewRoute()}}">{{isset($wishlist->product->product_translates)? $wishlist->product->product_translates->title : $wishlist->product->title}} <span>{{isset($wishlist->product->store->store_profile_translates)? $wishlist->product->store->store_profile_translates->name : $wishlist->product->store->name}}</span></a>
                            <a href="{{route('shop', $wishlist->product->store->slug)}}">{{isset($wishlist->product->store->store_profile_translates)? $wishlist->product->store->store_profile_translates->name : $wishlist->product->store->name}}</a>
                            </td>
                            <td class="product-price"><span class="amount">{{currency_format($wishlist->product->promo_price)}}</span></td>
                            <td class="product-quantity">
                                <span class="badge badge-pill {{$wishlist->product->stock > 0 ? 'badge-success' : 'badge-danger' }}  p-2">{{$wishlist->product->stock > 0 ? __("In Stock") : __("Out Of Stock") }}</span>
                            </td>
                            <td class="product-remove">
                                <a href="javascript:;" id="remove-{{$wishlist->product->id}}"  data-id="{{$wishlist->product->id}}" data-action="remove" class="wishlist">
                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                </a>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
         @else
         <div class="col-md-12 mt-3 text-center">
             <div class="no_product_found_img"><img alt="nowishlist" src="{{ asset('images') }}/nowishlist.png"> <h4 class="p-3">{{__('No item in your wishlist')}}</h4></div>
         </div>
        @endif
    </div>
</div>
