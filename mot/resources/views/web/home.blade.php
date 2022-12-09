@extends('web.layouts.app')
@section('content')
<!--=================
   Slider
   ==================-->
<div class="container">
   <div class="sliders-m mt-md-4">
      <div class="row">
         <div class="col-md-3">
             @if(!isMobileDevice())
                <!-- categoreis -->
                    @include('web.partials.all-categories')
                    <!-- categoreis Ends-->
             @endif
         </div>
         <div class="col-md-9">
            <div class="main_slider">
               <!-- slider start -->
               <div id="demo" class="carousel slide" data-ride="carousel">
                  <!-- The slideshow -->
                  <div class="carousel-inner">
                     @foreach($sliders as $slider)
                     <div class="carousel-item {{$loop->index == 0 ? 'active' : ''}}">
                         <a href="{{$slider->button_url}}">
                             @if(isMobileDevice())
                             <img src="{{isset($slider->slider_translates) ? $slider->slider_translates->getMedia('image', 'slider_mobile') : $slider->getMedia('image', 'slider_mobile')}}" alt="{{$loop->index}}">
                             @else
                             <img src="{{isset($slider->slider_translates) ? $slider->slider_translates->getMedia('image', 'slider') : $slider->getMedia('image', 'slider')}}" alt="{{$loop->index}}">
                             @endif
                         </a>
                     </div>
                     @endforeach
                  </div>
                  <!-- Indicators -->
                  <ul class="carousel-indicators">
                     @foreach($sliders as $slider)
                     <li data-target="#demo" data-slide-to="{{$loop->index}}" @if($loop->index == 0) class="active" @endif>
                        <a href="#"></a>
                      </li>
                     @endforeach
                  </ul>
                  <a class="carousel-control-prev" href="#demo" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                  </a>
                  <a class="carousel-control-next" href="#demo" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                  </a>
               </div>
            </div>
            <!-- slider ends -->
         </div>
      </div>
   </div>
</div>
@if($deals->count() > 0)
    @if(!isMobileDevice())
        @include('web.home-page-partials.daily-deals', $deals)
    @endif
@endif
@if ($flashDeals->count() > 0)
    @include('web.home-page-partials.flash-deals', $flashDeals)
@endif
@foreach($sections as $section)
    @switch($section->sortable_type)
        @case('App\Models\SponsorSection')
            @if(!isMobileDevice())
                @include('web.home-page-partials.sponsored-categories', ['section'=> $section , 'currency' => $currency])
            @endif
            @break
        @case('App\Models\TabbedSection')
            @if(!isMobileDevice())
                @include('web.home-page-partials.tabbed-products', ['section'=> $section , 'currency' => $currency])
            @endif
            @break
        @case('App\Models\Banner')
            @if(!isMobileDevice())
                @include('web.home-page-partials.banner', ['section'=> $section , 'currency' => $currency])
             @endif
            @break
        @case('App\Models\TrendingProduct')
            @include('web.home-page-partials.trending-products', ['section'=> $section , 'currency' => $currency])
            @break
    @endswitch
@endforeach

@endsection
@section('scripts')
<script>
//    function addCompareProduct(id) {
//        let product_id = id;
//        $('#loading-div-compare-' + id).removeClass('d-none');
//        $('#loading-div-compare').removeClass('d-none');
//        $.ajax({
//            type: "GET",
//            dataType: "json",
//            url: "{!! url('add-compare-product') !!}" + '/' + product_id,
//            success: function(data) {
//                if (data['success'] == true) {
//                    $('#loading-div-compare-' + id).addClass('d-none');
//                    ShowSuccessModal("{{trans('Product has been added to compare list')}}", 2000);
//                    $(".compare-btn").css("display", "block");
//                }
//            }
//        });
//    }

</script>

@endsection
