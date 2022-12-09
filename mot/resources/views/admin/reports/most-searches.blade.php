@extends('admin.layouts.app')

@section('content')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item active">{{ __($title) }}</li>
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
                                <a href="{{ route('admin.reports.coupon.usage.export', ['type' => 'xlsx']) }}?startDate={{$startDate}}&endDate={{$endDate}}&status={{$status}}&groupby={{$groupby}}" class="dropdown-item">Excel</a>
                                <a href="{{ route('admin.reports.coupon.usage.export', ['type' => 'csv']) }}?startDate={{$startDate}}&endDate={{$endDate}}&status={{$status}}&groupby={{$groupby}}" class="dropdown-item">CSV</a>
                                <a href="{{ route('admin.reports.coupon.usage.export', ['type' => 'pdf']) }}?startDate={{$startDate}}&endDate={{$endDate}}&status={{$status}}&groupby={{$groupby}}" class="dropdown-item">PDF</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- alerts -->
                        <x-alert class="alert-success" :status="session('success')" />
                        <x-alert class="alert-danger" :status="session('error')" />
                        <form action="{{ route('admin.reports.most-searches') }}" method="GET">
                            <div class="form-row align-items-center mb-3">
                                <div class="col-sm-3 my-1">
                                    <!--Start Date:-->
                                    <label for="startDate">Start Date</label>
                                    <input name="startDate" id="startDate" value="{{$startDate}}" width="200" autocomplete="false" />
                                </div>
                                <div class="col-sm-3 my-1">
                                    <!--End Date:-->
                                    <label for="endDate">End Date</label>
                                    <input name="endDate" id="endDate" value="{{$endDate}}" width="200" autocomplete="false" />
                                </div>
                                <div class="col-sm-3 my-1">
                                    <label for="endDate">Select Customer</label>
                                    <select class="custom-select select2 mr-sm-2" name="customer" id="customer">
                                        <option value="" selected>--{{__('All customers')}}--</option>
                                        @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}" @if (isset($request_params['customer']) && $request_params['customer'] == $customer->id) selected @endif >{{ $customer->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-auto my-1">
                                    <button type="submit" class="btn btn-success">{{__('Filter')}}</button>
                                    @if ( !empty($request_params) )
                                    <a href="{{ route('admin.reports.most-searches') }}" class="text-link" style="text-decoration: underline">{{__('Reset Filters')}}</a>
                                    @endif
                                </div>
                            </div>
                        </form>
                        <table class="table table-responsive-sm" id="datatables">
                            <thead>
                                <tr>
                                    <th class="text-center">{{ __('#') }}</th>
                                    <th class="text-center">{{ __('Keyword') }}</th>
                                    <th class="text-center">{{ __('Total Searches') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($records as $row)
                                <tr>
                                    <td class="text-center">{{$loop->iteration}}</td>
                                    <td class="text-center">{{$row->keyword}}</td>
                                    <td class="text-center">{{ $row->no_of_search }}</td>
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
  <x-admin.datatable-css />
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet">
  <link rel="stylesheet" href="//select2.github.io/select2-bootstrap-theme/css/select2-bootstrap.css" rel="stylesheet">
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
  <x-admin.datatable-js />
  <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
  <script type="text/javascript" charset="utf-8">
    $.fn.select2.defaults.set( "theme", "bootstrap" );
    $(document).ready(function(){
      var table = $('#datatables').DataTable({
        "iDisplayLength": 25,
        "order": [],
        "autoWidth": false,
        "aoColumnDefs": [
        { 'bSortable': true, 'aTargets': [ 0 ], 'name': 'order_number' },
        { 'bSortable': true, 'aTargets': [ 1 ], 'name': 'name' },
        { 'bSortable': true, 'aTargets': [ 2 ], 'name': 'total' },
        { 'bSortable': true, 'aTargets': [ 3 ], 'name': 'order_date' },
        { 'bSortable': true, 'aTargets': [ 4 ], 'name': 'status' },
        { 'bSortable': false, 'aTargets': [ 5 ], 'name': 'action' }
        ],
          "language": {
              "emptyTable": "{{__('No data available in table')}}",
              "zeroRecords": "{{__('No matching records found')}}",
              "search":      "{{__('Search:')}}"
          }
      });
      $( table.table().container() ).removeClass( 'form-inline' );
      $(".select2").select2({
        width: '100%'
      });
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
