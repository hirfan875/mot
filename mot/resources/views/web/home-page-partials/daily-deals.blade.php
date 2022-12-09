<div class="today_deals ">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="mb-3 float-left">{{__('Daily Deals')}}</h2>
                <ul class="cat_list">
                    <li><a href="{{route('daily-deals')}}" class="active">{{__('View All')}}</a></li>
                </ul>
            </div>

            <div class="col-md-12">
                <div id="daideals" class="owl-carousel owl-theme owl-carousel">
                @foreach($deals as $deal)
                <div class="item">
                    <a href="{{$deal->product->getViewRoute()}}">
                            <h3 class="disss">{{$deal->discount}} %</h3>
                            <span class="timer" ><span id="demos{{$deal->id}}" data-countdown="{{$deal->formatedEndingDate()}}"></span></span>

                            <div class="offers">
                                <h4>{{\Illuminate\Support\Str::limit(isset($deal->product->product_translates)? $deal->product->product_translates->title:$deal->product->title, 50) }}</h4>
                                <h5>{{currency_format($deal->product->promo_price, $currency)}}
                                    @if($deal->product->promo_price < $deal->product->price)
                                        <span class="offer_price">{{currency_format($deal->product->price, $currency)}}</span></h5>
                                @endif
                            </div>
                            <img loading="lazy" src="{{$deal->media_image(\App\Models\DailyDeal::DEAL_HOME)}}" alt="Deals">
                        </a>
                </div>

            @endforeach

                </div>
            </div>
        </div>
    </div>
</div>
