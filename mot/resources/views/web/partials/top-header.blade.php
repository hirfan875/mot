<?php $currentCurrency = getCurrency(); ?>
<?php $currentLanguage = getlanglist(app()->getLocale()) ?>
<!-- Button trigger modal -->
@if(get_option('top_notification_'.$currentLanguage->code) != "" && \Session::get('top_notification') != 1)
<div class="top_notification_div text-white pt-3 pb-3 pl-5" style="background-color: #218ce7;">
<div class="container">
    <div class="row">
        <div class="col-md-9">
            <p> {{get_option('top_notification_'.$currentLanguage->code)}}</p>
        </div>
        <div class="col-md-3">
            <span onclick="hideTopNotificationBar()" style="cursor: pointer;"><i class="fa fa-close"></i> {{__('Dismiss')}}</span>
        </div>
    </div>
</div>
</div>
@endif

@if(isMobileDevice())
<div class="top_header p-2 d-block d-md-none d-lg-none">
   <div class="container">
      <div class="row">
         <div class="col-md-12">
            <div class="d-flex justify-content-between">
               <span class="phone d--lg-inline-block d-none">{{__('Email us')}} : {{$header_email}}</span>
               <div class="account_setting">
                  <div class="d-flex justify-content-between">
                     <div class="dropdown">
                        <button class="dropdown-toggle" type="button"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <!-- {{__('Languages')}} -->
                        <!--<img height="15" alt="lazy" src="{{ asset('/assets/frontend') }}/assets/flags/{{$currentLanguage->emoji_uc}}.svg">-->

                        {{__($currentLanguage->native)}}
                        </button>
                         <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                             @foreach(getLocaleList() as $row)
                             <a class="dropdown-item {{ (app()->getLocale() == $row->code) ? 'active_lang' : '' }}" href="{{ url('locale/'.$row->code) }}"> <img height="15" loading="lazy" alt="emoji_uc" src="{{ asset('/assets/frontend') }}/assets/flags/{{$row->emoji_uc}}.svg">  {{__($row->native)}}</a>
                             @endforeach
                         </div>
                     </div>

                       <div class="dropdown border-right">
                          <button class="dropdown-toggle" type="button"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                           <img height="15" alt="lazy" src="{{ asset('/assets/frontend') }}/assets/flags/{{$currentCurrency->emoji_uc}}.svg">   {{$currentCurrency->code}}
                          </button>
                          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                              @foreach(getCurrenciesList() as $currency)
                              <a class="dropdown-item {{ ($currentCurrency->code == $currency->code) ? 'active_lang' : '' }}"  href="{{ route('currency',$currency->id )}}"><img height="15" alt="emoji_uc" loading="lazy" src="{{ asset('/assets/frontend') }}/assets/flags/{{$currency->emoji_uc}}.svg"> {{$currency->code}}</a>
                              @endforeach
                          </div>
                      </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@else

<div class="top_header p-2 d-md-block d-none">
   <div class="container">
      <div class="row">
         <div class="col-md-12">
            <div class="d-flex justify-content-between">
                <span class="phone">{{__('Support')}} :
                    @if ($header_contact_no != "")
                        @foreach(explode(',', $header_contact_no) as $contact_no)
                          <a href="tel:{{$contact_no}}">{{$contact_no}}</a>
                        @endforeach
                    @endif
                    {{__('Or Email us')}} : <a class="mailto" href="mailto:{{$header_email}}">{{$header_email}}</a></span>
               <div class="account_setting">
                  <div class="d-flex justify-content-between">
                     <div class="dropdown border-right">
                        <button class="dropdown-toggle" type="button"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                           {{__($currentLanguage->native)}}
                        </button>
                         <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                             @foreach(getLocaleList() as $row)
                                <a class="dropdown-item {{ (app()->getLocale() == $row->code) ? 'active_lang' : '' }}"  href="{{ url('locale/'.$row->code) }}"><img height="15" loading="lazy" src="{{ asset('/assets/frontend') }}/assets/flags/{{$row->emoji_uc}}.svg" alt="{{$row->native}}" /> {{$row->native}}</a>
                             @endforeach
                         </div>
                     </div>
                      <div class="dropdown border-right">
                          <button class="dropdown-toggle" type="button"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              <img height="15" alt="lazy" src="{{ asset('/assets/frontend') }}/assets/flags/{{$currentCurrency->emoji_uc}}.svg" alt="{{$currentCurrency->code}}" />   {{$currentCurrency->code}}
                          </button>
                          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                              @foreach(getCurrenciesList() as $currency)
                              <a class="dropdown-item {{ ($currentCurrency->code == $currency->code) ? 'active_lang' : '' }}"  href="{{ route('currency',$currency->id )}}"><img height="15" loading="lazy" src="{{ asset('/assets/frontend') }}/assets/flags/{{$currency->emoji_uc}}.svg" alt="{{$currency->code}}" /> {{$currency->code}}</a>
                              @endforeach
                          </div>
                      </div>
                     <div class="sell_on  pl-3">
                        <a href="{{route('seller-register')}}" class="text-secondary">{{__('Sell on MOT')}}</a>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endif
