@extends('web.layouts.app')
@section('content')
    <!--=================
  Start breadcrumb
  ==================-->
    <div class="breadcrumb-container">
        <h1>{{__('breadcrumb.order_cancel_request')}}</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('home')}}">{{__('breadcrumb.home')}}</a></li>
            <li class="breadcrumb-item"><a href="{{route('order-history')}}">{{__('breadcrumb.order_history')}}</a></li>
            <li class="breadcrumb-item"><a href="{{route('order-detail' ,$order->id) }}">{{__('breadcrumb.order_detail')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{__('breadcrumb.order_cancel_request')}}</li>
        </ol>
    </div>
    <!--=================
      End breadcrumb
      ==================-->

    <div class="container">
        <div class="bg-white pt-2 pl-5 pr-5 mt-minus">
            <div class="pb-5">

                <div class="row justify-content-center">
                    <div class="col-lg-6 p-3 bg-white rounded shadow-sm mb-2 mt-3">
                        <form method="post" action="{{route('cancel-order')}}">
                            <input type="hidden" name="order_id" value="{{$order->id}}">
                            @csrf
                            <div class="form-group">
                                <label for="reasonSelector">{{__('Select Reason')}}</label>
                                <select name="reason" class="form-control" id="reasonSelector" required>
                                    <option value="">{{__('Please select a reason to cancel this order.')}}</option>
                                    <option value="1" >{{__('Expected delivery date has changed and the product is arriving at a later date.')}}</option>
                                    <option value="2" >{{__('Product is being delivered to a wrong address(Customer’s mistake)')}}</option>
                                    <option value="3" >{{__('Product is not required anymore.')}}</option>
                                    <option value="4" >{{__('Cheaper alternative available for lesser price.')}}</option>
                                    <option value="5" >{{__('Bad review from friends/relatives after ordering the product.')}}</option>
                                    <option value="6" >{{__('Not going to be available in town due to some urgent travel.')}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="detailReason">{{__('Additional Information')}}</label>
                                <textarea name="notes" class="form-control" id="detailReason" placeholder="{{__('Optional')}}"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">{{__('Submit')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

@section('scripts')
    <script type="text/javascript">
        $(function () {
            $('[data-toggle="popover"]').popover()
        })
        $('#example').popover('show');
    </script>
@endsection
