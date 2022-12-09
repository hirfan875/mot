<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ (getLocalDir(app()->getLocale()))? getLocalDir(app()->getLocale()): 'ltr' }}">
   <head>
      <meta charset="utf-8">
      <title>{{ isset($meta_title) ? $meta_title :  getDefaultMetaTitle() }}</title>
      <link rel="icon" href="{{ cdn_url('/assets/backend/img/favicon.png') }}" type="image/x-icon">
      <meta content="width=device-width, initial-scale=1.0" name="viewport">
      <meta name="description" content="{{ isset($meta_description) ? strip_tags($meta_description) : strip_tags(getDefaultMetaDescription()) }}">
      <meta name="keywords" content="{{ isset($meta_keyword) ? $meta_keyword : getDefaultMetaKeywords() }}">
      <meta name="csrf-token" content="{{ csrf_token() }}" />
      <link rel="alternate" hreflang="{{ str_replace('_', '-', app()->getLocale()) }}" href="{{ url()->current() }}" />
      
      @if(request()->getHost() == 'v1.mallofturkeya.com')
        <meta name="facebook-domain-verification" content="7qal63ct0outx6l29gjecjbn13xfat" />
        @if(Route::current()->getName())
        @if(Route::current()->getName() == 'product')
            @if(isset($product))
            <meta property="og:title" content="{{ isset($meta_title) ? substr($meta_title, 0, 149) : '' }}">
            <meta property="og:description" content="{{ isset($meta_description) ? strip_tags(strtolower($meta_description)) : '' }}">
            <meta property="og:url" content="{{url()->current()}}">
            @if($product->gallery->count() > 0)
                @foreach($product->gallery as $key => $image)
                    @if($key == 0)
                    <meta property="og:image" content="{{$product->product_detail($key)}}">
                    @endif
                @endforeach
            @else
                <meta property="og:image" content="https://dummyimage.com/515x320/">
            @endif
            <meta property="product:brand" content="{{ isset($product->brand) ? $product->brand->title : ''}} ">
            <meta property="product:availability" content="in stock">
            <meta property="product:condition" content="new">
            <meta property="product:price:amount" content="{{ isset($product->price) ? $product->price : '' }}">
            <meta property="product:price:currency" content="TRY">
            <meta property="product:retailer_item_id" content="{{ isset($product->id) ?  $product->id : ''}}">
            <meta property="product:item_group_id" content="{{ isset($product->sku) ? $product->sku : ''}}">
          @endif
        @endif
        @endif
      @endif
      <!-- Bootstrap CSS File -->
       @if( getLocalDir(app()->getLocale()) == 'ltr')
        <link href="{{ cdn_url('/assets/frontend') }}/lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
       @else
       <link href="{{ cdn_url('/assets/frontend') }}/lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
       <link href="{{ asset('/assets/frontend') }}/assets/css/arabic.css" rel="stylesheet">
       <link href="https://cdn.rtlcss.com/bootstrap/v4.5.3/css/bootstrap.min.css">
       @endif
      <link href="{{ cdn_url('/assets/frontend') }}/assets/css/dropzone.css" rel="stylesheet">
      <!-- fancybox -->
      <link rel="stylesheet" type="text/css" href="{{ cdn_url('/assets/frontend') }}/assets/css/jquery.fancybox.min.css" >
      <!-- Main Stylesheet File -->
      <link href="{{ asset('/assets/frontend') }}/assets/css/style.css" rel="stylesheet">
      <link href="{{ asset('/assets/frontend') }}/assets/css/dev-style.css" rel="stylesheet">
      <link href="{{ asset('/assets/frontend') }}/assets/css/responsive.css" rel="stylesheet">
      <!-- carousel sliders -->
      <link rel="stylesheet" href="{{ cdn_url('/assets/frontend') }}/carousel/owl.carousel.min.css">
      <!-- Color  Stylesheet -->
      <link href="{{ cdn_url('/assets/frontend') }}/assets/css/theme_red.css" rel="stylesheet" id="pagestyle">
      
<!--      <link href="{{ cdn_url('/assets/frontend') }}/assets/css/AngularJS_Demo/simple-line-icons.min.css" rel="stylesheet" id="pagestyle">
      <link href="{{ cdn_url('/assets/frontend') }}/assets/css/AngularJS_Demo/font-awesome.min.css" rel="stylesheet" id="pagestyle">-->

      @yield('style')
      <script src="https://www.google.com/recaptcha/api.js" async defer></script>
      <script type="text/javascript">
        function get_action(form) {
            var v = grecaptcha.getResponse();
            if(v.length == 0)
            {
                document.getElementById('captcha').innerHTML="You can't leave Captcha Code empty";
                return false;
            }
            if(v.length != 0)
            {
                document.getElementById('captcha').innerHTML="Captcha completed";
                return true; 
            }
        }
    </script>
      
       @if(request()->getHost() == 'v1.mallofturkeya.com')
       @if(!isMobileDevice())
        <!-- Facebook Pixel Code -->
        <script>
          !function(f,b,e,v,n,t,s)
          {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
          n.callMethod.apply(n,arguments):n.queue.push(arguments)};
          if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
          n.queue=[];t=b.createElement(e);t.async=!0;
          t.src=v;s=b.getElementsByTagName(e)[0];
          s.parentNode.insertBefore(t,s)}(window, document,'script',
          'https://connect.facebook.net/en_US/fbevents.js');
          fbq('init', '223057829989560');
          fbq('track', 'PageView');
        </script>
        <noscript><img height="1" width="1" alt="Pixel" style="display:none"
          src="https://www.facebook.com/tr?id=223057829989560&ev=PageView&noscript=1"
        /></noscript>
        <!-- End Facebook Pixel Code -->
        <script id="mcjs">!function(c,h,i,m,p){m=c.createElement(h),p=c.getElementsByTagName(h)[0],m.async=1,m.src=i,p.parentNode.insertBefore(m,p)}(document,"script","https://chimpstatic.com/mcjs-connected/js/users/6cf790f44a62588dc0e4becd4/a3ab09bdf537c77ff4a0869bd.js");</script>
         @endif
         @endif

        @if(request()->getHost() == 'v1.mallofturkeya.com')
        <!-- Google Tag Manager -->
        <script>
            (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
                j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
                'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','GTM-M3PHNVP');
        </script>
        <!-- End Google Tag Manager -->
         @endif
   </head>
   <body class="lang_{{  getLocalDir(app()->getLocale()) }} {{ mb_strtolower(getLocalTitle(app()->getLocale()))  }}">
       
       @if(request()->getHost() == 'v1.mallofturkeya.com')
            <!-- Yandex.Metrika counter -->
             <script type="text/javascript" >
                (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
                m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
                (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");
                ym(88419310, "init", {
                     clickmap:true,
                     trackLinks:true,
                     accurateTrackBounce:true,
                     webvisor:true,
                     ecommerce:"dataLayer"
                });
             </script>
             <noscript><div><img src="https://mc.yandex.ru/watch/88419310" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
             <!-- /Yandex.Metrika counter -->
             
            <!-- Google Tag Manager (noscript) -->
            <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-M3PHNVP" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
            <!-- End Google Tag Manager (noscript) -->
       @endif
        
        <!--================= toaster ==================-->
        @include('web.partials.toaster')
        <!--================= toaster  ==================-->
        <!--================= Top Header ==================-->
        @include('web.partials.top-header')
        <!--=================  Header ==================-->

        @include('web.partials.header')

        @yield('content')

        @include('web.partials.newsletter')
<!--    <a id="popuplink" href="#inline" style="display:none;"></a>
      <div id="inline" style="display:none;text-align:center;">
      <div class="d-flex justify-content-between align-items-center">
        <div class="img_block_home"><img src="{{ cdn_url('/assets/frontend/assets/img/mottick-02.jpg') }}" alt="{{__('Mall Of Turkeya')}}" id="logo" width="600px"></div>
          <div class="text_block_home">
              <h2>{{__('Welcome to')}}</h2><h3>{{__('Mall Of Turkeya')}}</h3>
                <p>{{__('Buy The Best Turkish Made Products on Our Store')}}</p>
                <form action="https://mallofturkeya.us5.list-manage.com/subscribe/post?u=6cf790f44a62588dc0e4becd4&amp;id=5c5f26f180" method="post" id="mc-embedded-subscribe-forms" name="mc-embedded-subscribe-forms" class="validate" target="_blank" novalidate>
                    {{csrf_field()}}
                    <div class="subscribe_home">
                        <input type="email" value="" name="EMAIL" class="flds" id="mce-EMAILL" placeholder="{{__('Subscribe Now')}}">
                        <button class="input-btn" name="subscribee" id="mc-embedded-subscribes"><i class="fa fa-send"></i></button>
                        <h6 class="subscribed-message-success text-success"></h6>
                        <h6 class="subscribed-message-error text-danger"></h6>
                    </div>
                </form>
              {{--      <a href="#" class="go_home">Shop Now</a> --}}
          </div>
        </div>
         <a class="close-btnnn" href="javascript:;" onclick="jQuery.fancybox.close();"><i class="icon-close"></i></a>
      </div>-->
   <!-- Modal -->

<a href="{{url('/request-product')}}">
    <div class="request-prod-btn" >
        {{__('Request a Product')}}
    </div>
</a>

   <div class="up-btn" style="display: none;">
      <i class="fa fa-chevron-up"></i>
   </div>
   <style>
      .compare-btn { padding: 8px 14px 12px 14px; background: #ffbc00 !important; color: #fff; position: fixed; bottom: 100px; border-radius: 4px; cursor: pointer; right: 5px; display: none; }
      .toast:not(:last-child) { z-index: 9000;}
   </style>
   <a href="{{route('compare-product')}}" class="compare-btn">
      <i class="icon-shuffle"></i>
   </a>

   <!--================= Footer Start ==================-->
   @include('web.partials.footer')
   <!--================= Footer Start Ends  ==================-->

      <!-- JavaScript Libraries -->
      <script src="{{ cdn_url('/assets/frontend') }}/lib/jquery/jquery.min.js"></script>
      <script src="{{ cdn_url('/assets/frontend') }}/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
      <script src="{{ cdn_url('/assets/frontend') }}/lib/jquery/jquery.countdown.min.js"></script>
      <script src="{{ cdn_url('/assets/frontend') }}/assets/js/main.js"></script>
      <script src="{{ cdn_url('/assets/frontend') }}/assets/js/wishlist.js"></script>
      <script src="{{ cdn_url('/assets/frontend') }}/assets/js/ajax-submit.js"></script>
      <script src="{{ cdn_url('/assets/frontend') }}/carousel/owl.carousel.js"></script>
      <script src="{{ cdn_url('/assets/frontend') }}/assets/js/script.js"></script>
      <script src="{{ cdn_url('/assets/frontend') }}/assets/js/custom.js"></script>
      <script src="{{ cdn_url('/assets/frontend') }}/assets/js/search.js"></script>
      <script src="{{ cdn_url('/assets/frontend') }}/assets/js/mobile-search.js"></script>

<!--      <script src="{{ asset('/assets/frontend') }}/assets/js/jquery.lazyload.js"></script>
      <script type="text/javascript">
	$("img").lazyload({
	    effect : "fadeIn"
	});
      </script>-->

      <script>
          function swapStyleSheet(sheet){
              document.getElementById('pagestyle').setAttribute('href', sheet);
          }
      </script>

      <script src="{{ cdn_url('/assets/frontend') }}/assets/js/jquery.fancybox.min.js"></script>
      <script src="{{ cdn_url('/assets/frontend') }}/assets/js/jquery.cookie.js"></script>
      <script>
         jQuery(document).ready(function () {
            /*refresh page after click on browser back button start*/
            var perfEntries = performance.getEntriesByType("navigation");
            if (perfEntries[0].type === "back_forward") {
               location.reload(true);
            }
            /*refresh page after click on browser back button ends*/
            function openFancybox() {
               setTimeout(function () {
                  jQuery('#popuplink').trigger('click');
               }, 500);
            };
            var visited = jQuery.cookie('visited');
            if (visited == 'yes') {
               // second page load, cookie active
            } else {
              //openFancybox(); // first page load, launch fancybox
            }
            jQuery.cookie('visited', 'yes', {
              expires: 365 // the number of days cookie  will be effective
            });
            jQuery("#popuplink").fancybox({modal:true, maxWidth: 600, overlay : {closeClick : true}});
         });

      </script>
      @yield('scripts')

   <script type="text/javascript">
        $(document).ready(function () {
            $("#profileImage").click(function(e) {
                $("#imageUpload").click();
            });
            function fasterPreview( uploader ) {
                if ( uploader.files && uploader.files[0] ){
                      $('#profileImage1').attr('src', window.URL.createObjectURL(uploader.files[0]) );
                }
            }
            $("#imageUpload").change(function(){
                fasterPreview( this );
            });
        });

            $('#imageUploadForm').on('submit',(function() {
                var formData = new FormData(this);
                $.ajax({
                    type:'POST',
                    url: "{{ url('upload-avatar') }}",
                    data:formData,
                    cache:false,
                    contentType: false,
                    processData: false,
                    success:function(data){
//                        console.log("success");
//                        console.log(data);
                    },
                    error: function(data){
                    }
                });
            }));

            $("#imageUpload").on("change", function() {
                $("#imageUploadForm").submit();
            });

    </script>

       <script>
          function hideTopNotificationBar(){
              $.ajax({
                  type:'get',
                  url: "{{ url('dismiss_top_notification') }}",
                  success:function(data){
                      if(data){
                          $('.top_notification_div').addClass('d-none');
                      }
                  },
              });
           }
       </script>
<!--<script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script><script type='text/javascript'>(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';fnames[1]='FNAME';ftypes[1]='text';fnames[2]='LNAME';ftypes[2]='text';fnames[3]='ADDRESS';ftypes[3]='address';fnames[4]='PHONE';ftypes[4]='phone';fnames[5]='BIRTHDAY';ftypes[5]='birthday';}(jQuery));var $mcj = jQuery.noConflict(true);</script>-->
   <script type="text/javascript">
//       $(document).ready(function() {
//           $('#mc-embedded-subscribe-forms').submit(function(event) {
//               event.preventDefault();
//               let emailAddress = $('#mce-EMAILL').val();
//               if (!validateEmail(emailAddress)) {
//                   ShowFailureModal("Wrong email format");
//                   return false;
//               }
//               $.ajax({
//                   url :'{{route('newsletter')}}',
//                   type : "POST",
//                   data : $(this).serialize(),
//                   success : function(data) {
//                       if(data.result == 'error'){
//                           ShowFailureModal(data.msg);
//                       }
//                       if(data.result == 'success'){
//                           ShowSuccessModal(data.msg);
//                           location.reload();
//                       }
//                   },
//                   error: function (xhr, textStatus, errorThrown) {
//                       ShowFailureModal('Unable to Submit Request');
//                   }
//               });
//           });
//       });
       //validate email address
       function validateEmail(email) {
           const re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
           return re.test(email);
       }
   </script>
       <script src="{{ cdn_url('/js/share.js') }}"></script>
   </section>
   </body>
</html>
