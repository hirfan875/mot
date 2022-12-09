@extends('web.layouts.app')
@section('content')
<!--=================
  Start breadcrumb
  ==================-->
 <div class="breadcrumb-container">
    <h1>{{__('Order Return')}}</h1>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="#">{{__('Home')}}</a></li>
      <li class="breadcrumb-item"><a href="#">{{__('Library')}}</a></li>
      <li class="breadcrumb-item active" aria-current="page">{{__('Data')}}</li>
    </ol>
 </div>
<!--=================
  End breadcrumb
  ==================-->


  <div class="container">
    <!--=================
  Start Order Tracking
  ==================-->
    <div class="order-tracking mt-minus bg-white p-5">
      <h2>{{__('Order Details')}}</h2>

      <div class="row mt-4">

          <div class="col-md-12">
          <div class="card p-3">
            <h2 class="mb-2">{{__('Delivery Address')}}</h2>
            <p><strong></strong></p>
            <p></p>
            <h6 class="mt-3">{{__('Phone number')}}</h6>
            <p></p>
          </div>

        </div>


      </div>


<!-- Part 01 -->
      <div class="card mt-4 pb-5">
        <div class="row justify-content-between align-items-end p-3">
          <div class="col-md-6">
            <h5><i class="icon-present"></i> Package1 <span class="d-block">Sold by <a href="">Hall Road</a></span></h5>
          </div>
          <div class="col-md-6 text-sm-right">
            <p class="mb-0"> <a href="#." class="btn-primary"><i class="icon-bubble align-middle"></i> Need Help?</a></p>
          </div>
        </div>
        <hr class="m-0">
        <div class="row d-flex justify-content-between p-3">
          <div class="col-md-6">
            <h5>Delivered on 11 Jan 2020 23:53:44</h5>

<p class="mt-2 mb-2">
Color: Brown
Size: L</p>
          </div>
          <div class="col-md-6 text-sm-right">
            <p class="mb-0"><i class="icon-close"></i> Cancelled</p>
          </div>
        </div>

        <div class="table-content table-responsive table-spacer mr-6 ml-6 mt-3 mb-3 p-3">
          <table>
            <tbody>
              <tr>
                <td class="product-thumbnail">
                  <a href="#"><img src="assets/img/shoe.png" alt="cart-image"/></a>
                </td>
                <td class="product-name"><a href="#.">Jordon Premium <span>ONLINE SHOE STORE</span></a></td>
                <td class="product-price"><span class="amount">KWD 20</span></td>
                <td class="product-quantity">
                  <p><b>Qty:</b> 1</p>
                </td>

              </tr>
            </tbody>
          </table>
        </div>



            <div class="col-md-12 text-center">
  <!-- Add class 'active' to progress -->
     <ol class="progtrckr" data-progtrckr-steps="5">
    <li class="progtrckr-done">Order </li>
    <li class="progtrckr-todo">Cancelled</li>
</ol>

<div class="card delivery_message p-4">
            <!-- <div class="arrow-up"></div> -->
            <p>Return Reason<span class="pl-3 font-weight-bold">The delivery agent could not reach you</span></p>
          </div>

</div>

        </div>



      </div>



    </div>
    <!--=================
  End Order Tracking
  ==================-->

@endsection

{{--@section('scripts')--}}
{{--@endsection--}}
