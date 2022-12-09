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
                        {{ __($title) }}
                        <x-admin.add-button :url="route('admin.stores.add')" />
                    </div>
                    <div class="card-body">
                        <!-- alerts -->
                        <x-alert class="alert-success" :status="session('success')" />
                        <x-alert class="alert-danger" :status="session('error')" />
                        <table class="table table-responsive-sm" id="datatables">
                            <thead>
                                <tr>
                                    <th width="5%">{{ __('Status') }}</th>
                                    <th width="18%">{{ __('Name') }}</th>
                                    <th class="text-center" width="13%">{{ __('No.of Products') }}</th>
                                    <th class="text-center" width="13%">{{ __('No.of Active Products') }}</th>
                                    <th class="text-center" width="10%">{{ __('Phone') }}</th>
                                    <th class="text-center" width="10%">{{ __('Country') }}</th>
                                    <th class="text-center" width="10%">{{ __('Type') }}</th>
                                    <th class="text-center" width="10%">{{ __('Last updated') }}</th>
                                    <th class="text-center" width="23%">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($records as $row)
                                    <tr>
                                        <td>
                                            <x-admin.status-switcher :id="$row->id" :value="$row->status" />
                                        </td>
                                        <td><a href="{{ route('admin.stores.edit', ['store' => $row->id]) }}">{{ $row->name }}</a></td>
                                        <td class="text-center">{{ totalProductsByStore($row->id) }}</td>
                                        <td class="text-center">{{ totalActiveProductsByStore($row->id) }}</td>
                                        <td class="text-center">{{ $row->phone }}</td>
                                        <td class="text-center">{{ $row->country ? $row->country->title : '' }}</td>
                                        <td class="text-center">{{ $row->display_type }}</td>
                                        <td class="text-center">{{ date_format($row->updated_at,"d/m/Y H:i:s") }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.stores.staff', ['store' => $row->id]) }}" class="btn btn-outline-primary btn-sm mb-1">{{__('Staff')}}</a>
                                            <a href="{{ route('admin.stores.profile', ['store' => $row->id]) }}" class="btn btn-outline-warning btn-sm mb-1 text-dark">{{__('Profile')}}</a>
                                            <a href="{{ route('admin.stores.return.address', ['store' => $row->id]) }}" class="btn btn-outline-success btn-sm mb-1">{{__('Return Address')}}</a>
                                            <x-admin.edit-button :url="route('admin.stores.edit', ['store' => $row->id])" />
                                            @if ($row->is_approved == 0)
                                            <a href="{{ route('admin.stores.approve', ['store' => $row->id]) }}" class="btn btn-success btn-sm mb-1" onclick="return window.confirm('{{ __('Are you sure you want to approve :title', ['title' => $row->name.'?']) }}');">{{ __('Approve') }}</a>
                                            <a href="{{ route('admin.stores.reject', ['store' => $row->id]) }}" class="btn btn-danger btn-sm mb-1" onclick="return window.confirm('{{ __('Are you sure you want to reject :title', ['title' => $row->name.'?']) }}');">{{ __('Reject') }}</a>
                                            @endif
                                            <a href="{{ route('admin.validate.request', ['storeOrder' => $row->id]) }}" class="btn btn-outline-primary btn-sm mb-1">{{__('storeOrder')}}</a>
                                            <a href="{{ route('seller.login.admin',['id'=>$row->staff[0]->id ]) }}" class="btn btn-info btn-sm mb-1" style="background: transparent !important; color: blueviolet !important; border: blueviolet solid 1px !important; " target="_blank" >{{ __('login as seller') }}</a>
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
    $(document).ready(function(){
    var table = $('#datatables').DataTable({
        "iDisplayLength": 25,
        "order": [],
        "autoWidth": false,
        "aoColumnDefs": [
        { 'bSortable': false, 'aTargets': [ 0 ], 'name': 'status' },
        { 'bSortable': true, 'aTargets': [ 1 ], 'name': 'name' },
        { 'bSortable': false, 'aTargets': [ 2 ], 'name': 'phone' },
        { 'bSortable': true, 'aTargets': [ 3 ], 'name': 'country' },
        { 'bSortable': true, 'aTargets': [ 4 ], 'name': 'type' },
        { 'bSortable': true, 'aTargets': [ 5 ], 'name': 'updated_at' },
        { 'bSortable': false, 'aTargets': [ 6 ], 'name': 'action' }
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
<!-- update status -->
<x-admin.status-update-js :url="route('admin.stores.update.status')" />
@endpush
