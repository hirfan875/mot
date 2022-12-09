@extends('seller.layouts.app')

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
    <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item active">{{ __($title) }}</li>
</ol>
<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        {{ __($title) }}
                    </div>
                    <div class="card-body">
                        <div id="success"></div>
                        <x-alert class="alert-success" :status="session('success')" />
                        <x-alert class="alert-danger" :status="session('error')" />
                        <table class="table table-responsive-sm display select" id="datatables">
                            <thead>
                                <tr>
                                    <th width="22%">{{ __('Title') }}</th>
                                    <th class="text-center" width="10%">{{ __('Type') }}</th>
                                    <th class="text-center" width="12%">{{ __('Store') }}</th>
                                    <th class="text-center" width="5%">{{ __('Price') }}</th>
                                    <th class="text-center" width="11%">{{ __('SKU') }}</th>
                                    <th class="text-center" width="10%">{{ __('Last updated') }}</th>
                                    <th class="text-center" width="15%">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($records as $row)
                                <tr>
                                    <td>
                                        <a href="{{ route('seller.products.edit', ['product' => $row->id]) }}">{{ $row->product_translates ? $row->product_translates->title : $row->title }}</a>
                                    </td>
                                    <td class="text-center">{{ $row->type }}</td>
                                    <td class="text-center">{{ $row->store ? $row->store->store_profile_translates ? $row->store->store_profile_translates->name : $row->store->name : '' }}</td>
                                    <td class="text-center">{{ currency_format($row->price) }}</td>
                                    <td class="text-center">{{ $row->sku }}</td>
                                    <td class="text-center">{{ isset($row->updated_at) ? date_format($row->updated_at,"d/m/Y H:i:s") : '' }}</td>
                                    <td class="text-center">
                                    <button href="" class="btn btn-danger btn-sm mb-1">{{ __('Waiting for approval') }}</button>
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
    $(document).ready(function (){
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
                    "search":      "{{__('Search:')}}"
            },
            'order': [[1, 'asc']]
    });
    $(table.table().container()).removeClass('form-inline');
    // Handle click on "Select all" control
    $('#example-select-all').on('click', function(){
    // Get all rows with search applied
    var rows = table.rows({ 'search': 'applied' }).nodes();
    // Check/uncheck checkboxes for all rows in the table
    $('input[type="checkbox"]', rows).prop('checked', this.checked);
    });
    // Handle click on checkbox to set state of "Select all" control
    $('#datatables tbody').on('change', 'input[type="checkbox"]', function(){
    // If checkbox is not checked
    if (!this.checked){
    var el = $('#example-select-all').get(0);
    // If "Select all" control is checked and has 'indeterminate' property
    if (el && el.checked && ('indeterminate' in el)){
    // Set visual state of "Select all" control
    // as 'indeterminate'
    el.indeterminate = true;
    }
    }
    });
    // Handle form submission event
    $('#frm-example').on('click', function(e){
    var form = this;
    // Iterate over all checkboxes in the table
    table.$('input[type="checkbox"]').each(function(){
    // If checkbox doesn't exist in DOM
    if (!$.contains(document, this)){
    // If checkbox is checked
    if (this.checked){
    // Create a hidden element
    $(form).append(
            $('<input>')
            .attr('type', 'hidden')
            .attr('name', this.name)
            .val(this.value)
            );
    }
    }
    });
    });
    });
    function confirmIsApproved(isApproved, id){
    if (isApproved == 0){
        alert("{{trans('Brand not approved yet.')}}");
    return false;
    }
    if (confirm("{{trans('Are you sure you want to approve this product?')}}")){
        window.location.href = "{{ url('admin/products/approve') }}/" + id;
    }
    return false;
    }

    function confirmAllApproved(){
        if (confirm("{{trans('Are you sure you want to approve all product?')}}")){

            var values = $('input[type="checkbox"].myCheckbox:checked').map(function() {
            return $(this).val();
            }).toArray();
            var token = "{{ csrf_token() }}";
            $.ajax({
                type: 'POST',
                url: '{{ url("admin/products/approved/all") }}',
                data: JSON.stringify({_token:token, ids: values }),
                contentType: 'application/json',
                success: function (result) {
                    $('#success').html('<div class="alert alert-success alert-dismissible" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Rows Approved Successfuly</div>');
                    setTimeout(function(){// wait for 5 secs(2)
                        location.reload(); // then reload the page.(3)
                    }, 1000);
                }
            });
        }
        return false;
    }

    function confirmAllDelete(){
        if (confirm("{{trans('Are you sure you want to delete all product?')}}")){
            var values = $('input[type="checkbox"].myCheckbox:checked').map(function() {
            return $(this).val();
            }).toArray();
            var token = "{{ csrf_token() }}";
            $.ajax({
                type: 'POST',
                url: '{{ url("admin/products/deleted/all") }}',
                data: JSON.stringify({_token:token, ids: values }),
                contentType: 'application/json',
                success: function (result) {
                    $('#success').html('<div class="alert alert-success alert-dismissible" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Row Deleted Successfuly</div>');
                    setTimeout(function(){// wait for 5 secs(2)
                    location.reload(); // then reload the page.(3)
                    }, 1000);
                }
            });
        }
        return false;
    }

</script>
<script>
    $(document).ready(function(){
        $(".dropdown").on("hide.bs.dropdown", function(){
            $(".approve-delete").html('');
        });
        $(".dropdown").on("show.bs.dropdown", function(){
            $(".approve-delete").html('');
        });
    });
</script>
@endpush
