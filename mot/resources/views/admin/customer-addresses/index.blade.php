@extends('admin.layouts.app')

@section('content')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.customers') }}">{{ __($customer->name) }}</a></li>
    <li class="breadcrumb-item active">{{ __($title) }}</li>
  </ol>
  <div class="container-fluid">
    <div class="animated fadeIn">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              {{ __($title) }}
              <x-admin.back-button :url="route('admin.customers')" />
              <x-admin.add-button :url="route('admin.addresses.add', ['customer' => $customer->id])" />
            </div>
            <div class="card-body">
              <!-- alerts -->
              <x-alert class="alert-success" :status="session('success')" />
              <x-alert class="alert-danger" :status="session('error')" />
              <table class="table table-responsive-sm" id="datatables">
                <thead>
                  <tr>
                    <th width="12%">{{ __('Name') }}</th>
                    <th width="15%">{{ __('Email') }}</th>
                    <th class="text-center" width="11%">{{ __('Phone') }}</th>
                    <th class="text-center" width="11%">{{ __('City') }}</th>
{{--                    <th class="text-center" width="10%">{{ __('Zipcode') }}</th>--}}
                    <th class="text-center" width="10%">{{ __('State') }}</th>
                    <th class="text-center" width="11%">{{ __('Country') }}</th>
                    <th class="text-center" width="13%">{{ __('Last updated') }}</th>
                    <th class="text-center" width="18%">{{ __('Action') }}</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($records as $row)
                  <tr>
                    <td><a href="{{ route('admin.addresses.edit', ['customer' => $customer->id, 'address' => $row->id]) }}">{{ $row->name }}</a></td>
                    <td>{{ $row->customer->email }}</td>
                    <td class="text-center">{{ $row->phone }}</td>
                    <td class="text-center">{{ isset($row->cities->title) ? $row->cities->title : $row->city  }}</td>
{{--                    <td class="text-center">{{ $row->zipcode }}</td>--}}
                    <td class="text-center">{{ $row->states->title }}</td>
                    <td class="text-center">{{ isset($row->countries->title) ? $row->countries->title : $row->country }}</td>
                    <td class="text-center">{{ date_format($row->updated_at,"d/m/Y H:i:s") }}</td>
                    <td class="text-center">
                      @if ($row->is_default === 'yes')
                      <button type="button" class="btn btn-secondary btn-sm mb-1">{{ __('Default') }}</button>
                      @else
                      <a href="{{ route('admin.addresses.default', ['customer' => $customer->id, 'address' => $row->id]) }}" class="btn btn-primary btn-sm mb-1">{{ __('Set Default') }}</a>
                      @endif
                      <x-admin.edit-button :url="route('admin.addresses.edit', ['customer' => $customer->id, 'address' => $row->id])" />
                      <x-admin.delete-button :url="route('admin.addresses.delete', ['customer' => $customer->id, 'address' => $row->id])" :title="$row->name" />
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
        { 'bSortable': true, 'aTargets': [ 0 ], 'name': 'name' },
        { 'bSortable': true, 'aTargets': [ 1 ], 'name': 'email' },
        { 'bSortable': false, 'aTargets': [ 2 ], 'name': 'phone' },
        { 'bSortable': true, 'aTargets': [ 3 ], 'name': 'city' },
        { 'bSortable': true, 'aTargets': [ 4 ], 'name': 'zipcode' },
        { 'bSortable': true, 'aTargets': [ 5 ], 'name': 'country' },
        { 'bSortable': true, 'aTargets': [ 6 ], 'name': 'updated_at' },
        { 'bSortable': false, 'aTargets': [ 7 ], 'name': 'action' }
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
@endpush
