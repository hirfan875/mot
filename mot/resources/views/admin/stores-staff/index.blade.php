@extends('admin.layouts.app')

@section('content')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.stores') }}">{{ __($store->name) }}</a></li>
    <li class="breadcrumb-item active">{{ __($title) }}</li>
  </ol>
  <div class="container-fluid">
    <div class="animated fadeIn">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              {{ __($title) }}
              <x-admin.back-button :url="route('admin.stores')" />
              <x-admin.add-button :url="route('admin.stores.staff.add', ['store' => $store->id])" />
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
                    <th width="23%">{{ __('Email') }}</th>
                    <th class="text-center" width="13%">{{ __('Phone') }}</th>
                    <th class="text-center" width="10%">{{ __('Owner') }}</th>
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
                    <td><a href="{{ route('admin.stores.staff.edit', ['store' => $store->id, 'staff' => $row->id]) }}">{{ $row->name }}</a></td>
                    <td>{{ $row->email }}</td>
                    <td class="text-center">{{ $row->phone }}</td>
                    <td class="text-center">{{ $row->is_owner ? __('Yes') : __('No') }}</td>
                    <td class="text-center">{{ date_format($row->updated_at,"d/m/Y H:i:s") }}</td>
                    <td class="text-center">
                      <x-admin.edit-button :url="route('admin.stores.staff.edit', ['store' => $store->id, 'staff' => $row->id])" />
                      <x-admin.delete-button :url="route('admin.stores.staff.delete', ['store' => $store->id, 'staff' => $row->id])" :title="$row->name" />
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
        { 'bSortable': true, 'aTargets': [ 2 ], 'name': 'email' },
        { 'bSortable': false, 'aTargets': [ 3 ], 'name': 'phone' },
        { 'bSortable': true, 'aTargets': [ 4 ], 'name': 'is_owner' },
        { 'bSortable': true, 'aTargets': [ 5 ], 'name': 'updated_at' },
        { 'bSortable': false, 'aTargets': [ 6 ], 'name': 'action' }
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
  <x-admin.status-update-js :url="route('admin.stores.staff.update.status', ['store' => $store->id])" />
@endpush
