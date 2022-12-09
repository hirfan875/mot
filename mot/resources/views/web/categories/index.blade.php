@extends('web.layouts.app')
@section('content')

@section('style')
@endsection
<!--=================
  Start breadcrumb
  ==================-->
 <div class="breadcrumb-container">
    <h1>{{__('breadcrumb.all_categories')}}</h1>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{route('home')}}">{{__('breadcrumb.home')}}</a></li>
      <li class="breadcrumb-item active" aria-current="page">{{__('breadcrumb.categories')}}</li>
    </ol>
 </div>
<!--=================
  End breadcrumb
  ==================-->
<div class="container mt-minus">
    <div class="row mt-4">
        <div class="col-md-12 mb-4">
            <div class="seller_banner">
                <img loading="lazy" src="{{ cdn_url('/assets/frontend') }}/assets/img/all-products.jpg" alt="seller"/>
            </div>
        </div>
    </div>
    <div class="tab-content cate-page" id="pills-tabContent">
        <div class="tab-pane  active" id="tab4" role="tabpanel" aria-labelledby="payment-tab">
            <!-- Form Start From Here -->
            <div class="container">
                <div class="row products_container categories_items">
                    @foreach($categories as $category)
                    <div class="col-md-4">
                        <div class="products_wrapper">
                            <a href="{{route('category', $category->slug)}}">
                                <figure>
                                    @if($category->image != null)
                                        <img loading="lazy" src="{{ isset($category->category_translates->image) ? cdn_url('/storage/original/' . $category->category_translates->image) :  cdn_url('/storage/original/' . $category->image)}}" alt="category"/>
                                    @else
                                    <img loading="lazy" src="{{ asset('assets/frontend/assets/img/product-placeholder.jpg') }}" alt="category"/>
                                    @endif
                                </figure>
                                <h2>{{$category->category_translates ? $category->category_translates->title : $category->title}}</h2>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <!-- Form Ends From Here -->
        </div>
    </div>
</div>
@endsection
