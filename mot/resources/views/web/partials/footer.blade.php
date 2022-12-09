<section class="footer">
   <div class="container">
      <div class="row text-center text-xs-center text-sm-left text-md-left">
          <div class="col-md-12">
              <div class="row">
         <div class="col-xs-12 col-sm-3 col-md-3">
            <h5>{{__('Customer Services')}}</h5>
            <ul class="list-unstyled quick-links">
                <li><a href="{{url('/about-us')}}"><i class="fa fa-angle-double-right"></i>{{__('About Us')}}</a></li>
                <li><a href="{{url('/made-in-turkey')}}"><i class="fa fa-angle-double-right"></i>{{__('Made in Turkeya')}}</a></li>
                <li><a href="{{url('/mobile-app-icons-coming-soon')}}"><i class="fa fa-angle-double-right"></i>{{__('Mobile App Icons (Coming Soon)')}}</a></li>
            </ul>
         </div>
         <div class="col-xs-12 col-sm-3 col-md-3">
            <h5 style="border:0; " class="d-none d-lg-block">&nbsp;</h5>
            <ul class="list-unstyled quick-links quick-links_m">
                @if(getLatestOrder()!=null)
                <li><a href="{{url('/track-package/')}}/{{getLatestOrder()->id}}"><i class="fa fa-angle-double-right"></i>{{__('Order Tracking')}}</a></li>
                @endif
                <!--<li><a href="{{url('/sell-on-mot')}}"><i class="fa fa-angle-double-right"></i>{{__('Sell on Mot')}}</a></li>-->
                <!--<li><a href="{{url('/faq')}}"><i class="fa fa-angle-double-right"></i>{{__('FAQ')}}</a></li>-->
                <li><a href="{{url('/privacy-policy')}}"><i class="fa fa-angle-double-right"></i>{{__('Privacy Policy')}}</a></li>
                <li><a href="{{url('/terms-conditions')}}"><i class="fa fa-angle-double-right"></i>{{__('Terms & Conditions')}}</a></li>
                <li><a href="{{url('/return-and-refund-policy')}}"><i class="fa fa-angle-double-right"></i>{{__('Return Policy')}}</a></li>
                <!--<li><a href="{{url('/made-in-turkey')}}"><i class="fa fa-angle-double-right"></i>{{__('Made in Turkey')}}</a></li>-->
            </ul>
         </div>
          <!-- MOT B2B and B2C section -->
          <div class="col-xs-12 col-sm-3 col-md-3">
               <h5 class="mt-3">{{__('Follow Us')}}</h5>
               <p class="followtxt">{{__('Follow us to catch up on exciting discounts and offers.')}}</p>
              <div class="social_icons">
                  @if(get_option('social_facebook') !== null)
                      <a href="{{get_option('social_facebook')}}"><i class="fa fa-facebook"></i></a>
                  @endif
                  @if(get_option('social_twitter') !== null)
                      <a href="{{get_option('social_twitter')}}"><i class="fa fa-twitter"></i></a>
                  @endif
                  @if(get_option('social_linkedin') !== null)
                      <a href="{{get_option('social_linkedin')}}"><i class="fa fa-linkedin"></i></a>
                  @endif
                  @if(get_option('social_instagram') !== null)
                      <a href="{{get_option('social_instagram')}}"><i class="fa fa-instagram"></i></a>
                  @endif
                  @if(get_option('social_snapchat') !== null)
                      <a href="{{get_option('social_snapchat')}}"><i class="fa fa-snapchat"></i></a>
                  @endif
                  @if(get_option('social_youtube') !== null)
                      <a href="{{get_option('social_youtube')}}"><i class="fa fa-youtube"></i></a>
                  @endif
                  @if(get_option('social_pinterest') !== null)
                      <a href="{{get_option('social_pinterest')}}"><i class="fa fa-pinterest"></i></a>
                  @endif
              </div>
<!--            <h5>{{__('Trade Services')}}</h5>
            <ul class="list-unstyled quick-links">
               <li><a href="{{url('/trade-assurance')}}"><i class="fa fa-angle-double-right"></i>{{__('Trade Assurance')}}</a></li>
               <li><a href="{{url('/business-identity')}}"><i class="fa fa-angle-double-right"></i>{{__('Business Identity')}}</a></li>
               <li><a href="{{url('/logistics-service')}}"><i class="fa fa-angle-double-right"></i>{{__('Logistics Service')}}</a></li>
               <li><a href="{{url('/production-monitoring')}}"><i class="fa fa-angle-double-right"></i>{{__('Production Monitoring')}} </a></li>
               <li><a href="{{url('/blog')}}"><i class="fa fa-angle-double-right"></i>{{__('Blog')}}</a></li>
            </ul>-->
         </div>
         <div class="col-xs-12 col-sm-3 col-md-3 contactisrow">
{{--             <h5><a href="{{url('/contact-us')}}">{{__('Contact Us')}}</a></h5>--}}
            <ul class="list-unstyled quick-links icons">
                <li><i class="fa fa-envelope"></i> <a href="{{url('/contact-us')}}">{{__('Contact Us')}}</a> </li>
                <li><i class="fa fa-envelope"></i> <a class="mailto" href="mailto:{{$footer_email}}">{{$footer_email}}</a> </li>
                @if ($footer_contact_no != "")
                    @foreach(explode(',', $footer_contact_no) as $contact_no)
{{--                        <li><i class="fa fa-phone"></i><a href="https://api.whatsapp.com/send/?phone={{trim(preg_replace('/[^0-9]/', '', $contact_no))}}&text&app_absent=0">{{$contact_no}}</a> </li>--}}
                        <li><i class="fa fa-phone"></i><a href="tel:{{trim($contact_no)}}">{{$contact_no}}</a> </li>
                    @endforeach
                @endif
            </ul>
         </div>
      </div>
      </div>
      </div>
       <div class="text-center mt-4 pt-3 row d-none justify-content-center bg-white border-0">
           <div class="col-md-2"><img alt="brand1" loading="lazy" src="{{ cdn_url('/assets/frontend') }}/assets/img/home_products/brand-logo.jpg"/></div>
           <div class="col-md-2"><img alt="brand2" loading="lazy" src="{{ cdn_url('/assets/frontend') }}/assets/img/home_products/brand-logo1.jpg"/></div>
           <div class="col-md-2"><img alt="brand3" loading="lazy" src="{{ cdn_url('/assets/frontend') }}/assets/img/home_products/brand-logo3.jpg"/></div>
           <div class="col-md-2"><img alt="brand4" loading="lazy" src="{{ cdn_url('/assets/frontend') }}/assets/img/home_products/brand-logo4.jpg"/></div>
           <div class="col-md-2"><img alt="brand5" loading="lazy" src="{{ cdn_url('/assets/frontend') }}/assets/img/home_products/brand-logo5.jpg"/></div>
       </div>
      <div class="text-center mt-4 payments-logo">
         <!-- <img loading="lazy" src="{{ cdn_url('/assets/frontend') }}/assets/img/home_products/payment.jpg"> -->
        <img alt="logo1"  loading="lazy" src="https://www.myfatoorah.com/assets/img/logo1.jpg"/>
        <img alt="logo2"  loading="lazy" src="https://www.myfatoorah.com/assets/img/logo2.jpg"/>
        <img alt="logo3"  loading="lazy" src="https://www.myfatoorah.com/assets/img/logo3.jpg"/>
        <img alt="logo4"  loading="lazy" src="https://www.myfatoorah.com/assets/img/logo4.jpg"/>
        <img alt="logo5"  loading="lazy" src="https://www.myfatoorah.com/assets/img/logo5.jpg"/>
        <img alt="logo6"  loading="lazy" src="https://www.myfatoorah.com/assets/img/logo6.jpg"/>
        <img alt="logo7"  loading="lazy" src="https://www.myfatoorah.com/assets/img/logo7.jpg"/>
        <img alt="logo9"  loading="lazy" src="https://www.myfatoorah.com/assets/img/logo8.jpg"/>
        <img alt="logo9"  loading="lazy" src="https://www.myfatoorah.com/assets/img/logo9.jpg"/>
        <img alt="logo10"  loading="lazy" src="https://www.myfatoorah.com/assets/img/logo10.jpg"/>
        <img alt="logo11"  loading="lazy" src="https://www.myfatoorah.com/assets/img/logo11.jpg"/>
        <img alt="logo12"  loading="lazy" src="https://www.myfatoorah.com/assets/img/logo12.jpg"/>
        <img alt="logo13"  loading="lazy" src="https://www.myfatoorah.com/assets/img/logo13.jpg"/>
        <img alt="logo14"  loading="lazy" src="https://www.myfatoorah.com/assets/img/logo14.jpg"/>
        <img alt="logo15"  loading="lazy" src="https://www.myfatoorah.com/assets/img/logo15.jpg"/>
      </div>
      <div class="text-center mt-4  mb-1 copyright">
         <p>{{__('Mot ©')}} {{Date('Y')}} {{__('All rights reserved.')}}</p>
      </div>
   </div>
</section>
<div class="chat_window1">
<div class="messagepop pop">
    @if ($footer_contact_no != "")
        @foreach(explode(',', $footer_contact_no) as $contact_no)
        <a href="https://api.whatsapp.com/send/?phone={{trim(preg_replace('/[^0-9]/', '', $contact_no))}}&text&app_absent=0" class="whatsapp_icon">
            <img alt="whatsapp" loading="lazy" src="{{ cdn_url('/assets/frontend') }}/assets/img/whatsapp.svg"/>
        </a>
        @endforeach
    @endif
<!--     <a href="#" class="whatsapp_icon" id="FBLink">
         <img alt="messenger" loading="lazy" src="{{ cdn_url('/assets/frontend') }}/assets/img/messenger.svg" />
    </a>-->
</div>
<a  href="/#" id="contact" class="chat_btn">
    <img alt="close" class="cls" loading="lazy" src="{{ cdn_url('/assets/frontend') }}/assets/img/close.svg"/>
    <img alt="chat" class="chat" loading="lazy" src="{{ cdn_url('/assets/frontend') }}/assets/img/chat.svg"/>
</a>
</div>
<!-- Messenger Chat Plugin Code -->
<div id="fb-root"></div>
<!-- Your Chat Plugin code -->
<!--<div id="fb-customer-chat" class="fb-customerchat"></div>-->
<script>
//    let facebook=null;
//    (function(d, s, id) {
//        var js, fjs = d.getElementsByTagName(s)[0];
//        if (d.getElementById(id)) return;
//        js = d.createElement(s); js.id = id;
//        js.src = 'https://connect.facebook.net/ar_AR/sdk/xfbml.customerchat.js';
//        fjs.parentNode.insertBefore(js, fjs);
//    }(document, 'script', 'facebook-jssdk'));
//
//    var chatbox = document.getElementById('fb-customer-chat');
//    chatbox.setAttribute("page_id", "112634281077067");
//    chatbox.setAttribute("attribution", "biz_inbox");
//
//    window.fbAsyncInit = function() {
//        FB.init({
//            xfbml            : false,
//            version          : 'v11.0'
//        });
//        facebook=FB;
//    };
//    var fbImage  = document.getElementById('FBLink');
//    fbImage.onclick = function() {
//        facebook.XFBML.parse();
//        facebook.CustomerChat.show();
//    };

    var products = {!! $searchableProducts !!}
    $(document).ready(function(e1){
        $('#autocomplete').autocomplete({
            lookup: products,
            onSelect: function (suggestion) {
                var thehtml = '<a href="#"><strong>Product Name:</strong> ' + suggestion.title + ' <br> <strong>Symbol:</strong> </a>' + suggestion.data;
                $('#outputcontent').html(thehtml);
            }
        });
    });

    $(document).ready(function(e1){
        $('#mobile_autocomplete').autocomplete({
            lookup: products,
            onSelect: function (suggestion) {
                var thehtml = '<a href="#"><strong>Product Name:</strong> ' + suggestion.title + ' <br> <strong>Symbol:</strong> </a>' + suggestion.data;
                $('#outputcontent').html(thehtml);
            }
        });
    });
    
</script>
