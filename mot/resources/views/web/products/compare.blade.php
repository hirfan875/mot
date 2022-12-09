@extends('web.layouts.app')
@section('content')
<!--=================
Start breadcrumb
==================-->
<div class="breadcrumb-container">
    <h1>{{__('breadcrumb.compare_product')}}</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('home')}}">{{__('breadcrumb.home')}}</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{__('breadcrumb.compare_product')}}</li>
    </ol>
</div>
<!--=================
      End breadcrumb
      ==================-->
<div class="container">
    <div class="compare_products p-4 mt-minus bg-white">
        <div class="row">
            <div class="col-md-12">
                <div class="compare_box">
                    <div class="table-responsive">
                        <table class="table table-bordered text-center">
                            <tbody>
                                @foreach($fixedAttributes as $attribute => $title)
                                <tr class="pr_title">
                                    <td class="row_title">{{ $title }}</td>
                                    @if(isset($products))
                                    @foreach($products as $product)
                                    @switch ($attribute)
                                    @case('product-image')
                                    <td class="row_img">
                                        <img loading="lazy" src="{{$product->product_thumbnail()}}" alt="{{ $title }}"
                                             style="max-width:200px; max-height: 150px;">
                                    </td>
                                    @break
                                    @case('title')
                                    <td class="product_name"><a
                                            href="{{$product->getViewRoute()}}">{{\Illuminate\Support\Str::limit(isset($product->product_translates)? $product->product_translates->title : $product->title, 50)}}</a>
                                    </td>
                                    @break
                                    @case('price')
                                    <td class="product_price"><span class="price">
                                            <ccc title="{{currency_format($product->promo_price, $currency)}}"
                                                 class="ccc--converted"
                                                 style="font-size: inherit; display: inline; color: inherit;">{{currency_format($product->promo_price,$currency)}}</ccc>
                                        </span></td>
                                    @break
                                    @case('rating')
                                    <td>
                                        <div class="rating_wrap">
                                            <div class="rating">
                                                <div class="product_rate" style="width:80%"></div>
                                            </div>
                                            <span class="rating_num">({{$product->rating != null ? round($product->rating, 2) : 0}})</span>
                                        </div>
                                    </td>
                                    @break
                                    @case('data')
                                    <td class="row_desc">{!! isset($product->product_translates)? $product->product_translates->data : $product->$attribute !!}</td>
                                    @break

                                    @case('add-to-cart')
                                    @if ($product->stock > 0)
                                    <td class="row_btn"><a href="javascript:;"
                                                           onclick="addToCart({{$product->id}}, {{$product->stock}}, '{{$product->title}}', '{{$product->price}}')"
                                                           class="btn btn-fill-out"><i
                                                class="icon-basket-loaded"></i> {{__('Add To Cart')}}
                                        </a></td>
                                    @else
                                    <td>
                                        <p>{{__('Out of Stock')}}</p>
                                    </td>
                                    @endif
                                    @break

                                    @case('zzremoved')
                                    <td class="row_btn">
                                        <a href="javascript:;" onClick="removeCompare({{$product->id}})"
                                           class="btn btn-fill-out"><span
                                                id="spinner-{{$product->id}}"></span> {{__('Remove')}}
                                        </a>
                                    </td>
                                    @break
                                    {{-- Loop for Dynamic Attribures--}}
                                    @default
                                    @if(is_array($variableAttributes[strtolower($attribute)]))
                                    <td class="row_color">
                                        @if(isset($variableAttributes[$attribute][$product->id]))
                                        <div class="product_color_switch">
                                            @foreach(explode(", ",$variableAttributes[$attribute][$product->id]) as $color)
                                            <span
                                                style="background-color: {{ isset($color) ? $color : "N/A"  }};"></span> {{ isset($color) ? $color : "N/A"  }}
                                            @endforeach
                                        </div>
                                        @endif
                                    </td>
                                    @break
                                    @elseif($attribute == 'zzremoved')
                                    <td class="row_remove">
                                        <a href="#"><span>{{__('Remove')}}</span> <i
                                                class="fa fa-times"></i></a>
                                    </td>
                                    @break
                                    @endif
                                    {{-- <td class="row_dynamic">{{ isset($variableAttributes[$attribute][$product->id]) ? $variableAttributes[$attribute][$product->id] : "N/A"  }}</td>--}}
                                    @endswitch
                                    @endforeach
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
<script type="text/javascript">
    function removeCompare(productId) {
    $("#spinner-" + productId).html("<i class='fa fa-refresh fa-spin'></i> &nbsp;");
    var url = '{{ route("remove-compare-product", ":productId") }}';
    $.ajax({
    type: "GET",
            dataType: "json",
            url: url.replace(':productId', productId),
            success: function (data) {
            if (data.success) {
            $("#spinner-" + productId).hide();
            ShowSuccessModal("{{trans('compare.toaster.product_removed_success')}}");
            // location.reload(true);
            location.href = '{{route("compare-product")}}';
            }
            },
            error: function (error) {
            $("#spinner-" + productId).hide();
            ShowFailureModal(error.message);
            }
    });
    }
</script>
@endsection
