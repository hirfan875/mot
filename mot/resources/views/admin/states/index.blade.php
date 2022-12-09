@extends('admin.layouts.app')

@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.countries') }}">{{ $country->title }}</a></li>
        <li class="breadcrumb-item active">{{ $title }}</li>
    </ol>
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            {{ __($title) }}
                            <x-admin.back-button :url="route('admin.countries',['country' => $country->id])" />
                            <x-admin.add-button :url="route('admin.states.add', ['country' => $country->id])" />
                        </div>
                        <div class="card-body">
                            <!-- alerts -->
                            <x-alert class="alert-success" :status="session('success')" />
                            <x-alert class="alert-danger" :status="session('error')" />
                            <table class="table table-responsive-sm" id="datatables">
                                <thead>
                                <tr>
                                    <th width="5%">{{ __('Status') }}</th>
                                    <th width="25%">{{ __('Title') }}</th>
                                    <th class="text-center" width="15%">{{ __('Last updated') }}</th>
                                    <th class="text-center" width="15%">{{ __('Action') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($records as $row)
                                    <tr>
                                        <td>
                                            <x-admin.status-switcher :id="$row->id" :value="$row->status" />
                                        </td>
                                        <td><a href="{{ route('admin.states.edit', ['country' => $country->id, 'state' => $row->id]) }}">{{ $row->title }}</a></td>
                                        <td class="text-center">{{ isset($row->updated_at) ? date_format($row->updated_at,"d/m/Y H:i:s") : $row->updated_at }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.cities', ['country' => $country->id, 'state' => $row->id]) }}" class="btn btn-primary btn-sm mb-1">{{ __('Cities') }}</a>
                                            <x-admin.edit-button :url="route('admin.states.edit', ['country' => $country->id, 'state' => $row->id])" />
                                            <x-admin.delete-button :url="route('admin.states.delete', ['country' => $country->id, 'state' => $row->id])" :title="$row->title" />
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
                    { 'bSortable': true, 'aTargets': [ 1 ], 'name': 'title' },
                    { 'bSortable': true, 'aTargets': [ 2 ], 'name': 'updated_at' },
                    { 'bSortable': false, 'aTargets': [ 3 ], 'name': 'action' }
                ],
                "language": {
                    "emptyTable": "{{__('No data available in table')}}",
                    "zeroRecords": "{{__('No matching records found')}}",
                    "search":      "{{__('Search:')}}"
                }
            });
            $( table.table().container() ).removeClass( 'form-inline' );
        });
    </script>
    <!-- update status -->
    <x-admin.status-update-js :url="route('admin.states.update.status', ['country' => $country->id])" />
@endpush
