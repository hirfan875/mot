@if ($msg = Session::get('message'))
    <h2 id="message">{{$msg}}</h2>
@else
    <h2 id="message">&nbsp;</h2>
@endif
