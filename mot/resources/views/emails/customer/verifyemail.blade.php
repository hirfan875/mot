@component('mail::message')
    <h2 class="secondry_title">{{__('Hello')}}</h2>
    <p style="text-align: left !important;">{{__('Please click the link below to verify your email address.')}}</p>
    <div style="text-align:center;">
        <a href="#"   style="background: #E72128;
    border-color: #E72128;
    display:inline-block;
    color: #fff;
    padding:10px 30px;
    text-decoration:none;
    margin:0 auto 5px auto;
    border-radius: 0">{{__('Verify Email Address')}}</a>
    </div>
    <p style="text-align: left !important;">{{__('If you did not create an account, no further action is required')}}</p>
    <br>
    <p>{{__('Thanks')}},<br>
    {{ __(config('app.name')) }}</p>
@endcomponent
