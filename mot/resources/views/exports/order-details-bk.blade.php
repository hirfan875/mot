<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="http://fonts.googleapis.com/css2?family=Noto+Sans&display=swap" rel="stylesheet">
<link href="http://fonts.googleapis.com/css2?family=Amiri&display=swap" rel="stylesheet">
        <title>Email Template</title>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600&display=swap" rel="stylesheet">
        <style type="text/css">
            body{background:#f4f4f4; font-family: 'Poppins', sans-serif;}
.logo{position: absolute; left: 20px; top: 20px;}
.table_area td{vertical-align: top; font-size: 14px;}
.header{ background:#E72128; position: relative;  text-align:center;padding:60px 0 10px 0;}
.mainwrapper{width:560px;border-radius:10px 10px 15px 15px;background:#fff;margin:30px auto 0 auto; padding:0 0 0px 0px;}
.footer{margin:50px auto 0 auto;padding:30px 20px; overflow: hidden; background:#E7E7E7;}
.footer .left1{float: left; width: 45%;}
.footer .right1{float: right; width: 45%; text-align: right;}
.footer .right1 img{width: 35px; margin:10px 10px 0 0 ; }
.footer p{text-align:left ; margin: 0px;}
.main_title{font-size:22px; font-weight:normal; text-align:center;color:#fff; background: #E72128; font-weight: 600; padding:20px 0 30px 0; margin:0px 0 20px 0;}
p{font-size:14px; font-weight:normal; text-align:center;color:#4F4F4F;padding:0px; margin: 20px 12%;}
.link{text-align: center;}
.link a{color: #E72128; font-weight: 600;}
.primary_botton{text-align: center; margin: 0 15%;}
.primary_botton a{color: #fff; display: inline-block; width: 100%; padding:10px 0; text-decoration: none; margin: 20px 0; font-weight: 600; background: #E72128;}
.regards{font-size:14px; line-height: 22px; font-weight:normal; text-align:center;color:#666;padding:20px 0 0 0; margin:40px 0 0px 0 ; text-align: center;}
.regards p{font-size: 16px; color: #000;}
.alink{color: #4b9dab; text-decoration: none;}
.order_number{border-bottom:#ccc dashed  1px; margin:0 10%; font-weight: 600; color: #4F4F4F; padding: 0 0 15px 0; overflow: hidden;}
.order_number .order{float: left; width: 45%;}
.order_number .number{float: right; width: 45%; text-align: right;}
.secondry_title{text-align: center; font-size: 18px; margin: 30px 0 0 0; padding: 0;}
h3{text-align: center; font-size: 15px; color: #4F4F4F; font-weight: 500; margin:0px 0 40px 0; padding: 0;}
h4{text-align: center; font-size: 16px; color: #000; font-weight: 500; margin:0px 0 20px 0; padding: 0;}
.table_area{margin: 0 6%; }
.mt_top1{margin-top: 20px;}
.mt_bottom{margin-bottom: 20px;}
.table_area strong{color: #4F4F4F; font-weight: 400;}
.table_area .align-right{text-align: right; font-weight:500; color: #121212; }
hr{border-bottom: #ccc dashed 1px !important; margin: 30px 6% 20px 6%; border: 0; height: 0; background: transparent;}
.redMark{color: red;}
@media (max-width: 736px) {
0% !impor.mainwrapper{width:100%;}
.footer{width:100%; padding: 0;}
.footer  div{width:10tant; margin:10px 0 15px 0 !important; text-align:center !important;}
.footer  p{width:100% !important; padding:0 !important; margin:15px 0  5px 0!important; text-align:center !important;}
.mainwrapper div{ width:auto !important; }
.primary_botton{margin: 0;}
.mainwrapper{width: 100%;}
.logo {position: relative;left: auto;top: auto;}
.header{padding: 30px 0 10px 0;}
.primary_botton a {   display: block;    width: auto;    padding: 10px 0;    margin: 20px 5%;}
}
td{padding: 7px; font-size:13px; margin: 0 !important; border: 0;}
th{padding: 7px; font-size:13px; text-transform: uppercase; margin: 0 !important; border: 0;}
tr{border: 0;}

tr td:first-child { border-top-left-radius: 10px; }
tr td:last-child { border-top-right-radius: 10px; }
tr td:first-child { border-bottom-left-radius: 10px; }
tr td:last-child { border-bottom-right-radius: 10px; }

tr th:first-child { border-top-left-radius: 10px; }
tr th:last-child { border-top-right-radius: 10px; }
tr th:first-child { border-bottom-left-radius: 10px; }
tr th:last-child { border-bottom-right-radius: 10px; }
            HTML, BODY
            {
                font-family: Serif, arabic;
                font-family: DejaVu Sans, sans-serif;
                font-family: 'Amiri', serif;
            }
        
        </style>
    </head>
    <body style="background:#f4f4f4;">
        <div  class="mainwrapper">
      <!--       <div  class="header">
                <div class="logo">
                    <img src="{{'data:image/png;base64,'.base64_encode(file_get_contents(public_path('images/logo.png')))}}" width="120" alt="{{ config('app.name') }}"/>
                </div>
             </div>
            <h1 class="main_title">
                {{__('Order Invoice')}}
            </h1>
            <p>
                {{__('We’ve received your Order! Thank-you for your purchase from mallofturkeya.com')}}
            </p>
            <div class="order_number">
                <div class="order">{{__('Order')}}#</div>
                <div class="number">{!! $orders->order_number !!}</div>
            </div>
            <div class="order_number">
                <div class="order">{{__('Order Date')}}#</div>
                <div class="number"></div>
            </div>
            <h2 class="secondry_title">{{__('Thank-You')}}, {!! $orders->order->customer->name !!}</h2>
            <h3>{{__('Your Order')}} {{ __($orders->getStatus($orders->status)) }}</h3>
            @foreach($orders['order_items'] as $item)
            <div class="table_container mt_top1">
                <div class="table_area mt_top1 ">
                    <table width="100%" border="0" cellspacing="1" cellpadding="3">
                        <tbody>
                            <tr>
                                <td><strong>Product name </strong></td>
                                <td class="align-right">{!! $item['product']['title'] !!}</td>
                            </tr>
                            <tr>
                                <td ><strong>Quantity</strong></td>
                                <td class="align-right">{!! $item['quantity'] !!}</td>
                            </tr>
                            <tr>
                                <td ><strong>Price</strong></td>
                                <td class="align-right">{!! $item['unit_price'] !!}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            @endforeach
            <hr>
            <div class="table_area mt_top1 ">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                        <tr>
                            <td ><strong>Shipping Method</strong></td>
                            <td class="align-right">DHL</td>
                        </tr>
                        <tr>
                            <td ><strong>Payment Method</strong></td>
                            <td class="align-right">MyFatoorah</td>
                        </tr>
                        <tr>
                            <td ><strong>{{__('Sub Total')}}:</strong></td>
                            <td class="align-right">{!! $orders->sub_total !!}</td>
                        </tr>
                        @if($orders->getDiscount() > 0)
                        <tr>
                            <td><strong>{{__('Discount')}}:</strong></td>
                            <td class="align-right">-{!! $orders->getDiscount() !!}</td>
                        </tr>
                        @endif
                        <tr>
                            <td ><strong>{{__('Delivery Fee')}}:</strong></td>
                            <td class="align-right">{!! $orders->order->delivery_fee !!}</td>
                        </tr>
                        <tr>
                            <td ><strong>{{__('Order Total')}}:</strong></td>
                            <td class="align-right"><span class="redMark">{!! $orders->total !!}</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <hr/>
            <div class="table_area">
                <table width="100%" border="0" cellspacing="1" cellpadding="3">
                    <tbody><tr>
                            <td width="30%"><strong>{{__('Shipping To')}} </strong></td>
                            <td style="color:#666;">{!! $orders->order->customer->name !!}</td>
                        </tr>
                        <tr>
                            <td><strong>{{__('Address')}}</strong></td>
                            <td style="color:#666;">{!! str_replace(",","<br>",$orders->order->address) !!}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <hr/>
            <div class="regards">
                <p>
                    Best regards,<br>
                    Mall of Turkey <br>
                    <a href="{{ config('app.url') }}" class="alink">mallofturkeya.com</a>
                </p>
            </div>
            <div  class="footer1">
                <div class="left1">
                    <p>Copyright © {{ config('app.name') }}. All rights reserved.</p>
                </div>
                <div class="left1">
                    <p>
                    <a href="https://www.facebook.com/mallofturkeya" ><img src="{{'data:image/png;base64,'.base64_encode(file_get_contents(public_path('images/f.png')))}}" width="30" alt="" /></a>
                    <a href="https://twitter.com/?lang=en"><img src="{{'data:image/png;base64,'.base64_encode(file_get_contents(public_path('images/g.png')))}}" width="30" alt=""/></a>
                    <a href="https://www.instagram.com/mallofturkeya/"><img src="{{'data:image/png;base64,'.base64_encode(file_get_contents(public_path('images/in.png')))}}" width="30" alt=""/></a>
                    </p>
                </div>
            </div>
 -->




<!-- invoice new html start -->

<div class="inv_wrapper">
   <table style="background-image: linear-gradient(to right, #e5262a  , #871113); padding: 0 40px; min-height: 300px; display: flex; align-items: center; justify-content: space-between; border-radius:15px 15px 0 0">
    <tbody>
    <tr>
      <td>
       <img src="images/logo.svg" style="width: 150px;">
        <h3 style="padding: 0; margin: 30px 0 0 0; font-size: 24px; text-align: left; font-weight: bolder; color: #fff; text-transform: uppercase; ">Order No</h3>
        <h4 style="padding: 0; margin: -9px 0 0 0; font-size:13px; text-align: left; font-weight: bolder; color: #fff; text-transform: uppercase; ">#673547</h4>
      </td>
      <td>
        <div style="text-align: left; width: 180px;">
      <div style="margin-bottom: 8px;">
         <h6 style="color: #f3eaea; font-weight: 400; padding: 0; margin: 0; font-size:14px;">Order Date</h6>
       <p style="text-align:left;padding: 0; margin: 0; color: #f79496; font-size:12px;">10/11/22 (01:10 Am)</p>
      </div>
      <div style="margin-bottom: 8px;">
         <h6 style="color: #f3eaea; font-weight: 400; padding: 0; margin: 0; font-size:14px;"> Shipping Address</h6>
       <p style="text-align:left;padding: 0; margin: 0; color: #f79496; font-size:12px;">Make over <br> 345 5th avenue, New road City </p>
        <p style="text-align:left;padding: 0; margin:15px 0 0 0; color: #f79496; font-size:14px;">Total Amount <strong>KWD 908</strong> </p>
      </div>
      </div>
      </td>
    </tr>
    </tbody>
  </table>


<div style="background: #fff; padding: 20px; margin-top: -30px !important; width: 450px; border-radius: 20px; margin:0 auto;">
<table width="100%;" style="text-align:left; border-spacing: 0;">
    <tbody>
  <tr style="font-size:14px;  background:#f3f1f1; border-radius: 110px; overflow:hidden;">
    <th>No</th>
     <th>Item Description</th>
       <th>Qty</th>
      <th>Price</th>
        <th>Total</th>
  </tr>
  <tr>
    <td>01</td>
    <td>Iphone  <small style="color:#ccc; font-size: 11px; margin-top:-4px; display: block;">Seller Name</small><small style="color:#ccc; font-size: 11px; margin-top:-4px; display: block;">SKU: 75234</small></td>
    <td>2</td>
    <td><strong>KWD 787</strong></td>
    <td><strong>KWD 1487</strong></td>
  </tr>
    <tr style="background:#f3f1f1; border-radius: 110px; overflow:hidden;">
    <td>01</td>
    <td>Bag <span style=" font-size: 12px;">(Color:red, Size: M)</span>  <small style="color:#ccc; font-size: 11px; margin-top:-4px; display: block;">Seller Name</small><small style="color:#ccc; font-size: 11px; margin-top:-4px; display: block;">SKU: 75234</small></td>
    <td>2</td>
    <td><strong>KWD 787</strong></td>
    <td><strong>KWD 1487</strong></td>
  </tr><tr>
    <td>01</td>
    <td>Shoes <span style=" font-size: 12px;">(Color:White, Size: L)</span>  <small style="color:#ccc; font-size: 11px; margin-top:-4px; display: block;">Seller Name</small><small style="color:#ccc; font-size: 11px; margin-top:-4px; display: block;">SKU: 75234</small></td>
    <td>2</td>
    <td><strong>KWD 787</strong></td>
    <td><strong>KWD 1487</strong></td>
  </tr>
    <tr style="background:#f3f1f1; border-radius: 110px; overflow:hidden;">
    <td>02</td>
    <td>Dell Laptop  <small style="color:#ccc; font-size: 11px; margin-top:-4px; display: block;">Seller Name</small><small style="color:#ccc; font-size: 11px; margin-top:-4px; display: block;">SKU: 75234</small></td>
    <td>2</td>
    <td><strong>KWD 787</strong></td>
    <td><strong>KWD 1487</strong></td>
  </tr><tr>
    <td>03</td>
    <td>Iphone  <small style="color:#ccc; font-size: 11px; margin-top:-4px; display: block;">Seller Name</small><small style="color:#ccc; font-size: 11px; margin-top:-4px; display: block;">SKU: 75234</small></td>
    <td>2</td>
    <td><strong>KWD 787</strong></td>
    <td><strong>KWD 1487</strong></td>
  </tr>
 </tbody>
</table>
<div style="margin-top: 30px; margin-bottom:-30px;">
    <table width="100%" border="0" cellspacing="1" cellpadding="3">
      <tbody>
        
      <tr>
        <td><strong>Payment Method</strong></td>
        <td  align="right">VISA</td>
      </tr>
      <tr>
        <td><strong>Subtotal</strong></td>
        <td  align="right">KWD 345</td>
      </tr>
      <tr>
        <td><strong>Discount </strong></td>
        <td  align="right">2%</td>
      </tr>
      <tr>
        <td><strong>Shipping Fee </strong></td>
        <td  align="right">KWD 4</td>
      </tr>
       <tr>
        <td><strong style="font-size:18px">Order Total:</strong></td>
        <td valign="right"><span style="color: red; font-weight: 500; font-size: 22px; float: right;">KWD 4341</span></td>
      </tr>
    </tbody></table>
  </div>
</div>

<hr>




<div style="padding:10px 20px 20px 20px;">
  <h3 style="margin:0; padding:0; text-align:left; text-transform: uppercase; font-weight: 600;"> <a style="color: #333; text-decoration:none;" href="#">Terms and Conditions</a> </h3>

  <ul style="margin:0; padding: 5px 0 20px 20px; font-size: 14px; ">
    <li>Lorem ipsum text will be. </li>
     <li>All conditions applied on you product </li>
  </ul>
</div>




</div> 
</div>

<!-- invoice new html ends-->



        </div>
    </body>
</html>
