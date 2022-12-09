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
                        <x-admin.add-button :url="route('admin.permission.create')" />
                    </div>
                    <div class="card-body">
                        <!-- alerts -->
                        <x-alert class="alert-success" :status="session('success')" />
                        <x-alert class="alert-danger" :status="session('error')" />
                        <table class="table table-responsive-sm" id="datatables">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Name</th>
                                    <th>Guard Name</th>
                                    <th width="280px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($permissions as $key => $permission)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $permission->name }}</td>
                                    <td>{{ $permission->guard_name }}</td>	
                                    <td>
                                        <a class="btn btn-info" href="{{ route('admin.permission.show',$permission->id) }}"><i class="fa fa-eye"></i></a>
                                        @can('role-edit')
                                        <a class="btn btn-primary" href="{{ route('admin.permission.edit',$permission->id) }}"><i class="fa fa-edit"></i></a>
                                        @endcan
                                        @can('permission-delete')
                                        {!! Form::open(['method' => 'DELETE','route' => ['admin.permission.destroy', $permission->id],'style'=>'display:inline']) !!}
                                        {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                                        {!! Form::close() !!}
                                        @endcan
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {!! $permissions->render() !!}
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
    $(document).ready(function () {
        var table = $('#datatables').DataTable({
            "iDisplayLength": 25,
            "order": [],
            "autoWidth": false,
            "aoColumnDefs": [
                {'bSortable': false, 'aTargets': [0], 'name': 'id'},
                {'bSortable': true, 'aTargets': [1], 'name': 'name'},
                {'bSortable': false, 'aTargets': [2], 'name': 'action'}
            ],
            "language": {
                "emptyTable": "{{__('No data available in table')}}",
                "zeroRecords": "{{__('No matching records found')}}",
                "search": "{{__('Search:')}}"
            }
        });
        $(table.table().container()).removeClass('form-inline');
    });
</script>

@endpush
