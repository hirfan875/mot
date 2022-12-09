<!--=================
         Newsletter Start
         ==================-->
<section class="newsletter">

    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="content">
                    <!-- https://mallofturkeya.us5.list-manage.com/subscribe/post?u=6cf790f44a62588dc0e4becd4&amp;id=5c5f26f180 -->
                    <form action="{{route('newsletter')}}" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
                        {{csrf_field()}}
                        <h2>{{__('Trade Alert - Delivering the latest product trends and industry news straight to your inbox.')}}</h2>
                        <div class="input-group">
                            <i class="fa fa-envelope"></i>
                            <input type="email" value="" name="email" class="form-control required email" id="mce-EMAIL" placeholder="{{__('Enter your email')}}">
                            <span class="input-group-btn">
                               <input type="button" value="{{__('Subscribe')}}" name="subscribe" id="mc-embedded-subscribe" class="btn button" >
                            </span>
                        </div>
                        <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
                        <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_6cf790f44a62588dc0e4becd4_5c5f26f180" tabindex="-1" value=""></div>
                        <div class="clear"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
 
    <script src="{{ asset('assets/frontend') }}/ajax/lib/jquery.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#mc-embedded-subscribe').on('click',function(){
                $('#mc-embedded-subscribe-form').submit();
            });
            
            $('#mc-embedded-subscribe-form').submit(function(event) {
                event.preventDefault();
                var newsLetterEmail = $('#mce-EMAIL').val();
                /*check for blank email validation*/
                if(newsLetterEmail.trim() == ""){
                    ShowFailureModal("Please enter your email");
                    return false;
                }
                /*check for valid email address*/
                if(/(.+)@(.+){2,}\.(.+){2,}/.test(newsLetterEmail) === false){
                    ShowFailureModal("Please enter valid email address");
                    return false;
                }
                $.ajax({
                    url :'{{route('newsletter')}}',
                    type : "POST",
                    data : $(this).serialize(),
                    success : function(data) {
                        if(data.length > 0 ) {
                            data = JSON.parse(data);
                            if(data.httpStatus != 200){
                                ShowFailureModal(data.message);
                            }
                        } else {
                            ShowSuccessModal("Thank you for subscribe");
                        }
                    },
                    error: function (xhr, textStatus, errorThrown) {
                        ShowFailureModal('Unable to Submit Request');
                    }
                });
            });
        });
    </script>
</section>
