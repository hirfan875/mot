@extends('admin.layouts.app')

@section('content')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item active">{{ $title }}</li>
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
                        <!-- alerts -->
                        <x-alert class="alert-success" :status="session('success')" />
                        <x-alert class="alert-danger" :status="session('error')" />

                        <div class="form-group">
                            <label for="name">{{ __('Image') }}</label>
                            <span class="invalid-feedback d-block" > 
                                <strong>
                                    <a href="{{ route('admin.products.edit', ['product' => $records->order_item->product->id]) }}"> 
                                        <img src="{{$records->order_item->product->product_listing()}}" alt="" width="120">
                                    </a>
                                </strong> 
                            </span>
                        </div>
                        <div class="form-group">
                            <label for="name">{{ __('Product') }}</label>
                            <span class="invalid-feedback d-block" > <strong><a href="{{ route('admin.products.edit', ['product' => $records->order_item->product->id]) }}">{{ $records->order_item->product->product_translates ? $records->order_item->product->product_translates->title : $records->order_item->product->title }}</a></strong> </span>
                        </div><!-- comment -->
                        <div class="form-group">
                            <label for="name">{{ __('Customer') }}</label>
                            <span class="invalid-feedback d-block" > <strong><a href="{{ route('admin.customers.edit', ['customer' => $records->customer->id]) }}">{{ $records->customer->name }}</a></strong> </span>
                        </div>
                        <div class="form-group">
                            <label for="name">{{ __('Comment') }}</label>
                            <span class="invalid-feedback d-block" > <strong>{{ $records->comment }}</strong> </span>
                        </div>
                        <div class="form-group">
                            <label for="name">{{ __('Rating') }}</label>
                            <span class="invalid-feedback d-block" > <strong>{{ $records->rating }}</strong> </span>
                        </div>
                        <div class="form-group">
                            <label for="name">{{ __('Posted at') }}</label>
                            <span class="invalid-feedback d-block" > <strong>{{ $records->created_at }}</strong> </span>
                        </div>
                        <div class="form-group">
                            <label for="name">{{ __('Gallery') }}</label>
                            <span class="invalid-feedback d-block" > 

                                @foreach($records->gallery as $row)
                                @if($row->image != null)
                                <img src="{{ asset('/storage/original/'.$row->image) }}" alt="" width="60">
                                @endif
                                @endforeach
                            </span>
                        </div>
                        <div class="form-group">
                            <span class="invalid-feedback d-block" > 
                                @if (!$records->is_approved)
                                <a href="{{ route('admin.product.reviews.approve', ['item' => $records->id]) }}" class="btn btn-success btn-sm mb-1" onclick="return window.confirm('{{ __('Are you sure you want to approve this review?') }}');">{{ __('Approve') }}</a>
                                <a href="{{ route('admin.product.reviews.reject', ['item' => $records->id]) }}" class="btn btn-danger btn-sm mb-1" onclick="return window.confirm('{{ __('Are you sure you want to reject this review?') }}');">{{ __('Reject') }}</a>
                                @endif
                            </span>
                        </div>
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
    $(document).ready(function(){
    var table = $('#datatables').DataTable({
    "iDisplayLength": 25,
            "order": [],
            "autoWidth": false,
            "aoColumnDefs": [
            { 'bSortable': true, 'aTargets': [ 0 ], 'name': 'product' },
            { 'bSortable': true, 'aTargets': [ 1 ], 'name': 'customer' },
            { 'bSortable': false, 'aTargets': [ 2 ], 'name': 'comment' },
            { 'bSortable': true, 'aTargets': [ 3 ], 'name': 'rating' },
            { 'bSortable': true, 'aTargets': [ 4 ], 'name': 'created_at' },
            { 'bSortable': false, 'aTargets': [ 5 ], 'name': 'action' }
            ],
            "language": {
            "emptyTable": "{{__('No data available in table')}}",
                    "zeroRecords": "{{__('No matching records found')}}",
                    "search":      "{{__('Search:')}}"
            }
    });
    $(table.table().container()).removeClass('form-inline');
    });
</script>
@endpush
