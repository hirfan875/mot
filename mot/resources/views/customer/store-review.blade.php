@extends('web.layouts.app')
@section('content')
    <!--=================
  Start breadcrumb
  ==================-->
    <div class="breadcrumb-container">
        <h1>{{__('Seller Review')}}</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('home')}}">{{__('breadcrumb.home')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Seller Review')}}</li>
        </ol>
    </div>
    <!--=================
      End breadcrumb
      ==================-->

    <div class="container">
        <div class="my-account white-bg mt-minus">
            <div class="row">
                <div class="col-sm-3 user-sidebar">
                    <div class="nav flex-column nav-pills text-center user-nav pt-5" id="sidebar-admin" role="tablist" aria-orientation="vertical">
                        @include('customer.account-partial.profile-image')
                        @include('customer.account-partial.navigation', ['active'=>'order'])
                    </div>
                </div>

                <div class="col-sm-9 brder-left">
                    @include('customer.account-partial.feedback')
                    <div >
                    <!-- History List -->
                        @include('customer.account-partial.review-history')


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
