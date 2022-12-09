<div class="regards">
    <p>
        {{__('Best regards')}},<br>
        {{ config('app.name') }} <br>
        <a href="{{ config('app.url') }}" class="alink">{{ config('app.url') }}</a>
    </p>
</div>
<div  class="footer">
    <div class="left1">
        <p>{{ Illuminate\Mail\Markdown::parse($slot) }}</p>
    </div>
    <div class="right1">
        <a href="https://www.facebook.com/mallofturkeya" ><img alt="facebook" src="{{asset('images/f.png')}}" /></a>
        <a href="https://twitter.com/?lang=en"><img alt="twitter" src="{{asset('images/g.png')}}"/></a>
        <a href="https://www.instagram.com/mallofturkeya/"><img alt="instagram" src="{{asset('images/in.png')}}"/></a>
    </div>
</div> 