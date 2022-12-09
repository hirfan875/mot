@extends('admin.layouts.app')

@section('content')
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
<style>
    /* Style to reverse the caret icon from pointing downwards to upwards */
    .caret.caret-up {
        border-top-width: 0;
        border-bottom: 4px solid #fff;
    }
</style>

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
                        {{ __($title) }}
                        <x-admin.add-button :url="route('admin.customers.add')" />
                    </div>
                    <div class="card-body">
                        <!-- alerts -->
                        <x-alert class="alert-success" :status="session('success')" />
                        <x-alert class="alert-danger" :status="session('error')" />
                        <table class="table table-responsive-sm" id="datatables">
                            <thead>
                            <tr>
                                <th width="3%" class="check">
                                    <div class="dropdown" style="float:left;">

                                        <button class="btn btn-primary dropdown-toggle approve-delete" type="button"
                                                data-toggle="dropdown">
                                        </button>
                                        <ul class="dropdown-menu">
                                            <!--<li><a href="#" onclick="confirmAllUpdateStatus();">Status</a></li>-->
                                            <li><a href="#" onclick="confirmAllDelete();">Delete</a></li>
                                        </ul>
                                    </div>
                                    <div class="dropdown" style="float:left;"><input type="checkbox" name="select_all"
                                                                                     value="1" id="example-select-all">
                                    </div>

                                </th>
                                <th width="5%">{{ __('Status') }}</th>
                                <th width="7%">{{ __('ID') }}</th>
                                <th width="15%">{{ __('Name') }}</th>
                                <th width="15%">{{ __('Email') }}</th>
                                <th width="10%">{{ __('Type of Customer') }}</th>
                                <th class="text-center" width="10%">{{ __('Number of Orders') }}</th>
                                <th class="text-center" width="10%">{{ __('Wallet Recharge') }}</th>
                                <th class="text-center" width="14%">{{ __('Last updated') }}</th>
                                <th class="text-center" width="20%">{{ __('Action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($records as $row)
                                <tr>
                                    <td>
                                        <input class="myCheckbox" type="checkbox" name="id[]" value="{{$row->id}}">
                                    </td>
                                    <td>
                                        <x-admin.status-switcher :id="$row->id" :value="$row->status"/>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.customers.edit', ['customer' => $row->id]) }}">{{ $row->id }}</a>
                                    </td>
                                    <td>
                                        <a data-toggle="tooltip" data-placement="top" title="{{$row->no_of_login > 1 ? __('old') : __('new')}}" href="{{ route('admin.customers.edit', ['customer' => $row->id]) }}">{{ $row->name }}</a>
                                    </td>
                                    <td>{{ $row->email }}</td>
                                    <td>{{ ($row->is_guest == 1 ) ? 'Guest':'Register'}}</td>
                                    <td class="text-center">{{totalOrdersByCustomerId($row->id)}} </td>
                                    <td class="text-center">0</td>
                                    <td class="text-center">{{ date_format($row->updated_at,"d/m/Y H:i:s") }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.addresses', ['customer' => $row->id]) }}" class="btn btn-primary btn-sm mb-1">{{ __('Addresses') }}</a>
                                        <x-admin.edit-button :url="route('admin.customers.edit', ['customer' => $row->id])"/>
                                        <x-admin.delete-button :url="route('admin.customers.delete', ['customer' => $row->id])" :title="$row->title"/>
                                    </td>
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
@endpush

@push('footer')
<x-admin.datatable-js />
<script type="text/javascript" charset="utf-8">
//    $(document).ready(function(){
//      var table = $('#datatables').DataTable({
//        "iDisplayLength": 25,
//        "order": [],
//        "autoWidth": false,
//        "aoColumnDefs": [
//        { 'bSortable': false, 'aTargets': [ 0 ], 'name': 'status' },
//        { 'bSortable': true, 'aTargets': [ 1 ], 'name': 'id' },
//        { 'bSortable': true, 'aTargets': [ 2 ], 'name': 'name' },
//        { 'bSortable': true, 'aTargets': [ 3 ], 'name': 'email' },
//        { 'bSortable': true, 'aTargets': [ 4 ], 'name': 'username' },
//        { 'bSortable': true, 'aTargets': [ 5 ], 'name': 'orders' },
//        { 'bSortable': true, 'aTargets': [ 6 ], 'name': 'updated_at' },
//        { 'bSortable': false, 'aTargets': [ 7 ], 'name': 'action' }
//        ],
//          "language": {
//              "emptyTable": "{{__('No data available in table')}}",
//              "zeroRecords": "{{__('No matching records found')}}",
//              "search":      "{{__('Search:')}}"
//          }
//      });
//      $( table.table().container() ).removeClass( 'form-inline' );
//    });
</script>
<script type="text/javascript" charset="utf-8">
    $(document).ready(function () {
        var table = $('#datatables').DataTable({
            "iDisplayLength": 25,
            "autoWidth": false,
            'columnDefs': [{
                    'targets': 0,
                    'searchable': false,
                    'orderable': false,
                    'className': 'dt-body-center',
                }],
            "language": {
                "emptyTable": "{{__('No data available in table')}}",
                "zeroRecords": "{{__('No matching records found')}}",
                "search": "{{__('Search:')}}"
            },
            'order': [[1, 'asc']]
        });
        $(table.table().container()).removeClass('form-inline');
        // Handle click on "Select all" control
        $('#example-select-all').on('click', function () {
            // Get all rows with search applied
            var rows = table.rows({'search': 'applied'}).nodes();
            // Check/uncheck checkboxes for all rows in the table
            $('input[name="id[]"]', rows).prop('checked', this.checked);
        });
        // Handle click on checkbox to set state of "Select all" control
        $('#datatables tbody').on('change', 'input[type="checkbox"]', function () {
            // If checkbox is not checked
            if (!this.checked) {
                var el = $('#example-select-all').get(0);
                // If "Select all" control is checked and has 'indeterminate' property
                if (el && el.checked && ('indeterminate' in el)) {
                    // Set visual state of "Select all" control
                    // as 'indeterminate'
                    el.indeterminate = true;
                }
            }
        });
        // Handle form submission event
        $('#frm-example').on('click', function (e) {
            var form = this;
            // Iterate over all checkboxes in the table
            table.$('input[type="checkbox"]').each(function () {
                // If checkbox doesn't exist in DOM
                if (!$.contains(document, this)) {
                    // If checkbox is checked
                    if (this.checked) {
                        // Create a hidden element
                        $(form).append($('<input>').attr('type', 'hidden').attr('name', this.name).val(this.value));
                    }
                }
            });
        });
    });
    function confirmIsApproved(isApproved, id) {
        if (isApproved == 0) {
            alert("{{trans('Brand not approved yet.')}}");
            return false;
        }
        if (confirm("{{trans('Are you sure you want to approve this product?')}}")) {
            window.location.href = "{{ url('admin/customers/approve') }}/" + id;
        }
        return false;
    }

    function confirmAllUpdateStatus() {
        if (confirm("{{trans('Are you sure you want to change Status of all selected product?')}}")) {

            var values = $('input[name="id[]"].myCheckbox:checked').map(function () {
                return $(this).val();
            }).toArray();
            var token = "{{ csrf_token() }}";
            $.ajax({
                type: 'POST',
                url: '{{ url("admin/customers/status/all") }}',
                data: JSON.stringify({_token: token, ids: values}),
                contentType: 'application/json',
                success: function (result) {
                    $('#success').html('<div class="alert alert-success alert-dismissible" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Rows Approved Successfuly</div>');
                    setTimeout(function () {// wait for 5 secs(2)
                        location.reload(); // then reload the page.(3)
                    }, 1000);
                }
            });
        }
        return false;
    }

    function confirmAllDelete() {
        if (confirm("{{trans('Are you sure you want to delete all product?')}}")) {
            var values = $('input[name="id[]"].myCheckbox:checked').map(function () {
                return $(this).val();
            }).toArray();
            var token = "{{ csrf_token() }}";
            $.ajax({
                type: 'POST',
                url: '{{ url("admin/customers/deleted/all") }}',
                data: JSON.stringify({_token: token, ids: values}),
                contentType: 'application/json',
                success: function (result) {
                    $('#success').html('<div class="alert alert-success alert-dismissible" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Row Deleted Successfuly</div>');
                    setTimeout(function () {// wait for 5 secs(2)
                        location.reload(); // then reload the page.(3)
                    }, 1000);
                }
            });
        }
        return false;
    }

</script>
<script>
    $(document).ready(function () {
        $(".dropdown").on("hide.bs.dropdown", function () {
            $(".approve-delete").html('');
        });
        $(".dropdown").on("show.bs.dropdown", function () {
            $(".approve-delete").html('');
        });
    });
</script>
<!-- update status -->
<x-admin.status-update-js :url="route('admin.customers.update.status')" />
@endpush
