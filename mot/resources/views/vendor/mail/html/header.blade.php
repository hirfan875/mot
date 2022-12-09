<div  class="header" style="background:#E72128; position: relative; text-align:center; padding:60px 0 10px 0;">
    <div class="logo" style="position: absolute; left: 20px; top: 20px;">
        <img src="{{asset('images/logo.png')}}" alt="{{ $slot }}" title="{{ $slot }}" style="display:block; margin-left: auto; margin-right: auto;" width="120" data-auto-embed="attachment"/>
    </div>
    @if($headerImageName != null)
        <img src="{{asset('/images/'.$headerImageName)}}" alt="{{ $slot }}" title="{{ $slot }}" style="display:block; margin-left: auto; margin-right: auto;" width="120" data-auto-embed="attachment"/>
    @else
        <img src="{{asset('/images/email_varification.png')}}" alt="{{ $slot }}" title="{{ $slot }}" style="display:block; margin-left: auto; margin-right: auto;" width="120" data-auto-embed="attachment"/>
    @endif
</div>
