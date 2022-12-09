@extends('admin.layouts.app')
@section('content')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">{{ $title }}</li>
</ol>
<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        {{ $title }}
                        
                        <div class="btn-group pull-right mr-2">
                            <button class="btn btn-warning btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-download" aria-hidden="true"></i> &nbsp;Export</button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="{{ route('admin.reports.sales.export', ['type' => 'xlsx']) }}?startDate={{$startDate}}&endDate={{$endDate}}&status={{$status}}" class="dropdown-item">Excel</a>
                                <a href="{{ route('admin.reports.sales.export', ['type' => 'csv']) }}?startDate={{$startDate}}&endDate={{$endDate}}&status={{$status}}" class="dropdown-item">CSV</a>
                                <a href="{{ route('admin.reports.sales.export', ['type' => 'pdf']) }}?startDate={{$startDate}}&endDate={{$endDate}}&status={{$status}}" class="dropdown-item">PDF</a>
                            </div>
                        </div>
                        
                    </div>
                    <div class="card-body">
                        @if (session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
                        @if (session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif
                        <form  action="{{ route('admin.reports.sales') }}" method="get" class="mb-3">
                            <div class="table-responsive">
                                <table>
                                    <tr>
                                        <td width="10%">Filter by</td>
                                        <td width="20%">
                                            <div class="container">
                                                Start Date: <input name="startDate" id="startDate" value="{{$startDate}}" width="200" autocomplete="false" />
                                                  <!--End Date: <input id="endDate" width="276" />-->
                                            </div>
                                        </td>
                                        <td width="20%">
                                            <div class="container">
                                                  <!--Start Date: <input id="startDate" width="276" />-->
                                                End Date: <input name="endDate" id="endDate" value="{{$endDate}}" width="200" autocomplete="false" />
                                            </div>
                                        </td>
                                        <td width="20%" class="pr-3">
                                            Order Status: 
                                            <select name="status" id="status" class="custom-select ml-2 mr-2">
                                                <option value="">--status--</option>
                                                <option value="0" @if( $status == 0 ) selected @endif >{{__('Uninitiated')}}</option>
                                                <option value="1" @if( $status == 1 ) selected @endif >{{__('Confirmed')}}</option>
                                                <option value="2" @if( $status == 2 ) selected @endif >{{__('Paid')}}</option>
                                                <option value="3" @if( $status == 3 ) selected @endif >{{__('Ready To Ship')}}</option>
                                                <option value="4" @if( $status == 4 ) selected @endif >{{__('Shipped')}}</option>
                                                <option value="5" @if( $status == 5 ) selected @endif >{{__('Delivered')}}</option>
                                                <option value="6" @if( $status == 6 ) selected @endif >{{__('Cancellation Requested')}}</option>
                                                <option value="7" @if( $status == 7 ) selected @endif >{{__('Cancelled')}}</option>
                                                <option value="8" @if( $status == 8 ) selected @endif >{{__('Return Requested')}}</option>
                                                <option value="9" @if( $status == 9 ) selected @endif >{{__('Delivery Failure')}}</option>
                                            </select>
                                        </td>
                                        <td width="30%">
                                            <button id="filter" type="submit" class="btn btn-success ml-2">Filter</button>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </form>
                        <table class="table table-responsive-sm" id="datatables">
                            <thead>
                                <tr>
                                    <th class="text-center" width="9%">Order #</th>
                                    <th class="text-center" width="7%">User ID</th>
                                    <th class="text-center" width="15%">User Name</th>
                                    <th class="text-center" width="10%">Total</th>
                                    <th class="text-center" width="12%">Ordered Currency</th>
                                    <th class="text-center" width="13%">Status</th>
                                    <th class="text-center" width="13%">Order Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($records as $row)
                                <tr>
                                    <td class="text-center">{{ (isset($row->order))? $row->order_number:$row->id }}{{ $row->order_number }}</td>
                                    <td class="text-center">{{ isset($row->customer) ? $row->customer->id : '' }}</td>
                                    <td class="text-center">
                                        @if(isset($row->customer))
                                        <a href="{{ route('admin.customers.edit', ['customer' => $row->customer->id]) }}" target="_blank">{{ $row->customer->name }}</a>
                                        @endif
                                    </td>
                                    <td class="text-center">{{__('TRY')}} {{ number_format($row->total, 2, ".", ","); }}</td>
                                    <td class="text-center">{{__($row->currency->code)}}&nbsp;{{convertTryForexRate($row->total, $row->forex_rate, $row->base_forex_rate, $row->currency->code)}}</td>
                                    <td class="text-center">
                                        @if ( $row->status == 0 )
                                            {{__('Uninitiated')}}
                                        @elseif ( $row->status == 1 )
                                            {{__('Confirmed')}}
                                        @elseif ( $row->status == 2 )
                                            {{__('Paid')}}
                                        @elseif ( $row->status == 3 )
                                            {{__('Ready To Ship')}}
                                        @elseif ( $row->status == 4 )
                                            {{__('Shipped')}}
                                        @elseif ( $row->status == 5 )
                                            {{__('Delivered')}}
                                        @elseif ( $row->status == 6 )
                                            {{__('Cancellation Requested')}}
                                        @elseif ( $row->status == 7 )
                                            {{__('Cancelled')}}
                                        @elseif ( $row->status == 8 )
                                            {{__('Return Requested')}}
                                        @elseif ( $row->status == 9 )
                                            {{__('Delivery Failure')}}
                                        @endif
                                    </td>
                                    <td class="text-center">{{ Carbon\Carbon::parse($row->created_at)->format('M j, Y g:i A') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('header')
<link rel="stylesheet" href="{{ asset('assets/backend') }}/css/dataTables.bootstrap4.css" type="text/css" />
@endpush

@push('footer')
<x-admin.datatable-js />
  <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
  
<script type="text/javascript" src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
<script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
<link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" charset="utf-8">
$(document).ready(function () {
  var table = $('#datatables').DataTable({
      'iDisplayLength': 25,
      "order": [],
      "autoWidth": false,
      "aoColumnDefs": [
          {'bSortable': true, 'aTargets': [0], 'name': 'id'},
          {'bSortable': true, 'aTargets': [1], 'name': 'customer_id'},
          {'bSortable': true, 'aTargets': [2], 'name': 'name'},
          {'bSortable': true, 'aTargets': [3], 'name': 'total'},
          {'bSortable': true, 'aTargets': [4], 'name': 'payment_method'},
          {'bSortable': true, 'aTargets': [5], 'name': 'status'},
          {'bSortable': true, 'aTargets': [6], 'name': 'created_at'}
      ]
  });
  $(table.table().container()).removeClass('form-inline');
});
</script>

<script>
    var today = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate());
    $('#startDate').datepicker({
        uiLibrary: 'bootstrap4',
        iconsLibrary: 'fontawesome',
        format: 'yyyy-mm-dd',
        maxDate: function () {
            return $('#endDate').val();
        },
        onSelect: function (selected) {
            $("#endDate").datepicker("option", "minDate", selected)
        }
    });
    $('#endDate').datepicker({
        uiLibrary: 'bootstrap4',
        iconsLibrary: 'fontawesome',
        format: 'yyyy-mm-dd',
        minDate: function () {
            return $('#startDate').val();
        },
        onSelect: function (selected) {
            $("#startDate").datepicker("option", "maxDate", selected)
        }
    });

    $(document).ready(function ()
    {
        $("#filter").click(function (event) {        
            var date_ini = getDate($('#startDate').val());
            var date_end = getDate($('#endDate').val());

        });
    });

    function getDate(input)
    {
        from = input.split("-");
        return new Date(from[2], from[1] - 1, from[0]);
    }
</script>
@endpush