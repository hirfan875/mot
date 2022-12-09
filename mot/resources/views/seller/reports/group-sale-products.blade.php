@extends('seller.layouts.app')
@section('content')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}">Dashboard</a></li>
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
                                <a href="{{ route('seller.report.group.sales.products.export', ['type' => 'xlsx']) }}?startDate={{$startDate}}&endDate={{$endDate}}&status={{$status}}&groupby={{$groupby}}" class="dropdown-item">Excel</a>
                                <a href="{{ route('seller.report.group.sales.products.export', ['type' => 'csv']) }}?startDate={{$startDate}}&endDate={{$endDate}}&status={{$status}}&groupby={{$groupby}}" class="dropdown-item">CSV</a>
                                <a href="{{ route('seller.report.group.sales.products.export', ['type' => 'pdf']) }}?startDate={{$startDate}}&endDate={{$endDate}}&status={{$status}}&groupby={{$groupby}}" class="dropdown-item">PDF</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if (session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
                        @if (session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif
                        <form  action="{{ route('seller.report.group.sales.products') }}" method="get" class="mb-3">
                            <div class="table-responsive">
                                <table>
                                    <tr>
                                        <td width="10%">Filter by</td>
                                        <td width="10%">
                                            <div class="container">
                                                Start Date: <input name="startDate" id="startDate" value="{{$startDate}}" width="200" autocomplete="false" />
                                            </div>
                                        </td>
                                        <td width="10%">
                                            <div class="container">
                                                End Date: <input name="endDate" id="endDate" value="{{$endDate}}" width="200" autocomplete="false" />
                                            </div>
                                        </td>
<!--                                        <td width="20%" class="pr-3">
                                            Order Status: 
                                            <select name="status" id="status" class="custom-select ml-2 mr-2">
                                                <option value="" @if( $status === '' ) selected @endif>--Ordre Status--</option>
                                                <option value="0" @if( $status === 0 ) selected @endif >{{__('Uninitiated')}}</option>
                                                <option value="1" @if( $status === 1 ) selected @endif >{{__('Confirmed')}}</option>
                                                <option value="2" @if( $status === 2 ) selected @endif >{{__('Paid')}}</option>
                                                <option value="3" @if( $status === 3 ) selected @endif >{{__('Ready To Ship')}}</option>
                                                <option value="4" @if( $status === 4 ) selected @endif >{{__('Shipped')}}</option>
                                                <option value="5" @if( $status === 5 ) selected @endif >{{__('Delivered')}}</option>
                                                <option value="6" @if( $status === 6 ) selected @endif >{{__('Cancellation Requested')}}</option>
                                                <option value="7" @if( $status === 7 ) selected @endif >{{__('Cancelled')}}</option>
                                                <option value="8" @if( $status === 8 ) selected @endif >{{__('Return Requested')}}</option>
                                                <option value="9" @if( $status === 9 ) selected @endif >{{__('Delivery Failure')}}</option>
                                            </select>
                                        </td>-->
                                        <td width="20%" class="pr-3">
                                            Group By: 
                                            <select name="groupby" id="groupby" class="custom-select ml-2 mr-2">
                                                <option value="">--Calendar--</option>
                                                <option value="Yearly" @if( $groupby == 'Yearly' ) selected @endif >Yearly</option>
                                                <option value="Monthly" @if( $groupby == 'Monthly' ) selected @endif >Monthly</option>
                                                <option value="Daily" @if( $groupby == 'Daily' ) selected @endif >Daily</option>
                                             </select>
                                        </td>
                                        <td width="10%">
                                            <button id="filter" type="submit" class="btn btn-success ml-2">Filter</button>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </form>
                        <table class="table table-responsive-sm" id="datatables">
                            <thead>
                                <tr>
                                    <th class="text-center" width="20%">{{__('Title')}}</th>
                                    <th class="text-center" width="10%">{{__('Total Order')}}</th>
                                    <th class="text-center" width="20%">{{__('Total Unit Price')}}</th>
                                    <th class="text-center" width="20%">{{__('quantity')}}</th>
                                    <th class="text-center" width="15%">{{__('Date')}} </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($records as $row)
                                <tr>
                                    <td class="text-center">{{ $row['product']['title'] }}</td> 
                                    <td class="text-center">{{ $row['countTotal'] }}</td>
                                    <td class="text-center">{{__('TRY')}} {{ number_format($row['unit_price'], 2, ",", "."); }}</td>
                                    <td class="text-center">{{ $row['quantity'] }}</td> 
                                    <td class="text-center">{{ $row['date'] }}</td>
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
<script type="text/javascript" src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" /><!-- comment -->
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
          {'bSortable': true, 'aTargets': [0], 'name': 'countTotal'},
          {'bSortable': true, 'aTargets': [1], 'name': 'amountTotal'},
          {'bSortable': true, 'aTargets': [1], 'name': 'deliveryFee'},
          {'bSortable': true, 'aTargets': [1], 'name': 'total'},
          
          {'bSortable': true, 'aTargets': [2], 'name': 'date'}
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
        // minDate: today,
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