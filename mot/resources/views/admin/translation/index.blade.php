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
                            <form action="{{route('admin.translation', ['language' => $language->id])}}" method="GET">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="keyword">{{__('Search keyword')}}</label>
                                        <input type="text" id="keyword" name="keyword" class="form-control"
                                               value="{{isset(request()->keyword) ? request()->keyword : null}}">
                                    </div>
                                    <div class="col-md-2 mt-4">
                                        <button type="submit" class="btn btn-success">{{__('Search')}}</button>
                                        <a href="{{route('admin.translation', ['language' => $language->id])}}" class="btn btn-danger">{{__('Reset')}}</a>
                                    </div>
                                </div>
                            </form>

                            <x-admin.add-button :url="route('admin.translation.add', ['language' => $language->id])"/>
                        </div>
                        <div class="card-body">
                            <!-- alerts -->
                            <x-alert class="alert-success" :status="session('success')"/>
                            <x-alert class="alert-danger" :status="session('error')"/>
                            <table class="table table-responsive-sm" {{--id="datatables"--}}>
                                <thead>
                                <tr>
                                    <th width="5%">{{ __('Status') }}</th>
                                    <th width="15%">{{ __('Keyword') }}</th>
                                    <th width="15%">{{ __('Translate') }}</th>
                                    <th class="text-center" width="15%">{{ __('Last updated') }}</th>
                                    <th class="text-center" width="25%">{{ __('Action') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($records as $row)
                                    <tr>
                                        <td>
                                            <x-admin.status-switcher :id="$row->id" :value="$row->status"/>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.languages.edit', ['language' => $language->id,'language' => $row->id]) }}">{{ $row->key }}</a>
                                        </td>
                                        <td class="text-center">{{ $row->translate }}</td>
                                        <td class="text-center">{{ isset($row->updated_at) ? date_format($row->updated_at,"d/m/Y H:i:s") : '' }}</td>
                                        <td class="text-center">
                                            <x-admin.edit-button
                                                :url="route('admin.translation.edit', ['language' => $language->id,'translate' => $row->id])"/>
                                            @if ( $row->is_default != 'Yes' )
                                                <x-admin.delete-button
                                                    :url="route('admin.translation.delete', ['language' => $language->id,'translate' => $row->id])"
                                                    :title="$row->key"/>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    {!! $records->appends(Request::all())->links() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('header')
    <x-admin.datatable-css/>
@endpush

@push('footer')
    <x-admin.datatable-js/>
    <script type="text/javascript" charset="utf-8">
        $(document).ready(function () {
            var table = $('#datatables').DataTable({
                "iDisplayLength": 25,
                "order": [],
                "autoWidth": false,
                "aoColumnDefs": [
                    {'bSortable': false, 'aTargets': [0], 'name': 'status'},
                    {'bSortable': true, 'aTargets': [1], 'name': 'key'},
                    {'bSortable': true, 'aTargets': [2], 'name': 'translate'},
                    {'bSortable': true, 'aTargets': [3], 'name': 'updated_at'},
                    {'bSortable': false, 'aTargets': [4], 'name': 'action'}
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
    <!-- update status -->
    <x-admin.status-update-js :url="route('admin.translation.update.status', ['language' => $language->id])"/>
@endpush
