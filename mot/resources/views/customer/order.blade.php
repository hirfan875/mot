@extends('web.layouts.app')
@section('content')
    <!--=================
  Start breadcrumb
  ==================-->
    <div class="breadcrumb-container">
        <h1>{{__('breadcrumb.order_history')}}</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('home')}}">{{__('breadcrumb.home')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{__('breadcrumb.order_history')}}</li>
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
                        @include('customer.account-partial.history')


                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script type="text/javascript">
        $(function () {
            $('[data-toggle="popover"]').popover()
        })
        $('#example').popover('show');

        $(function() {
            $('input[name="daterange"]').daterangepicker({
                opens: 'left',
                locale: { cancelLabel: 'Clear' }
            }, function(start, end, label) {
                console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
                $('#start_date').val( start.format('YYYY-MM-DD') );
                $('#end_date').val( end.format('YYYY-MM-DD') );
            });

            $('input[name="daterange"]').on('cancel.daterangepicker', function(ev, picker) {
                //do something, like clearing an input
                $('#daterange').val('');
            });
        });
    </script>
@endsection
