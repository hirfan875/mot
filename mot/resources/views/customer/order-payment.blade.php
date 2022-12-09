@extends('web.layouts.app')
@section('content')
<!--=================
  Start breadcrumb
==================-->
<div class="breadcrumb-container">
    <h1>{{__('order-success.breadcrumb_title')}}</h1>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{route('home')}}">{{__('site.home')}}</a></li>
      <li class="breadcrumb-item active" aria-current="page">{{__('order-success.breadcrumb_title')}}</li>
    </ol>
 </div>
<!--=================
  End breadcrumb
==================-->

<div class="container-fluid">
    <!--=================
        Start Thank You
        ==================-->
    <div class="thank-you mt-minus bg-white">
        <div class="row text-center p-5">
            <div class="col-md-6">
                <img src="{{ asset('assets/frontend') }}/assets/img/smile.png" alt="smile">
                <h1 class="mt-4 mb-3">{{__('order-payment.please_pay_for_your_order')}}</h1>
            </div>
            <div class="col-lg-6 p-3  bg-white rounded shadow-sm mb-2 mt-3">
                <!-- Orders table -->
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col" class="border-0 bg-light" width="50%">
                                </th>
                                <th scope="col" class="border-0 bg-light" width="300"></th>
                                <th scope="col" class="border-0 bg-light" width="300"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td scope="row" class="border-0">
                                    <div class="p-2">
                                        <h5>{{__('order-success.shipping_address')}}</h5>
                                        <p class="text-muted">{!! str_replace(",","<br>",$order->address) !!}
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- End -->
            </div>
        </div>
    </div>
    <!--=================
        End Thank You
    ==================-->
</div>
@endsection
