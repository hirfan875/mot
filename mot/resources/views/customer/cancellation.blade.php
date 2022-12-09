@extends('web.layouts.app')
@section('content')
    <!--=================
  Start breadcrumb
  ==================-->
    <div class="breadcrumb-container">
        <h1>{{__('breadcrumb.my_cancellation')}}</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('home')}}">{{__('breadcrumb.home')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{__('breadcrumb.my_cancellation')}}</li>
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
                        @include('customer.account-partial.navigation', ['active'=>'cancellation'])
                    </div>
                </div>

                <div class="col-sm-9 brder-left">
                    @include('customer.account-partial.feedback')
                    <div class="tab-content">
                    <!-- Tab mycancelation List -->
                    @include('customer.account-partial.cancellation')

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
