@component('mail::message', ['headerImageName' => 'order_cancel2.png'])
    <h1 class="main_title">{{ __('Registered Successfully!') }}</h1>
    <h1 style="text-align: center; padding:0px 35px">Welcome!</h1>
    <p>{{$customer->email}}</p>
    <p style="text-align: left !important;">Thank you for registering at <strong style="color:#E72128">Mallofturkeya.com</strong></p>
    <p style="text-align: left !important;">Your account has been created and you can login by using this email address and password by visiting our website or clicking at the following URL.</p>
    <div class="primary_botton">
        <a href="{{url('login-register')}}">{{url('login-register')}} </a>
    </div>
    <p style="text-align: left !important;">Upon Logging in, you will be able to access other services including reviewing past orders, printing invoices and editing your account information.</p>
    <hr>
    <div class="table_area">
        <table width="100%" border="0" cellspacing="1" cellpadding="3">
            <tbody>
                <tr>
                    <td width="30%" align="center"> <img src="{{asset('images/icon1.jpg')}}" width="50"> <br> Express Delivery</td>
                    <td width="30%" align="center"> <img src="{{asset('images/icon2.jpg')}}" width="50"> <br> Return Guarantee</td>
                    <td width="30%" align="center"> <img src="{{asset('images/icon3.jpg')}}" width="50"> <br> Secure Shopping</td>
                </tr>
            </tbody>
        </table>
    </div>
    <hr>
    <h3>Customer Support : +965 99732998 | +90 5355103999 </h3>
@endcomponent
