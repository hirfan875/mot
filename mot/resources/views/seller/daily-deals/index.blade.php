@extends('seller.layouts.app')

@section('content')
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
              <x-admin.add-button :url="route('seller.daily.deals.add')" />
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
                    <th class="text-center" width="10%">{{ __('Discount') }}</th>
                    <th class="text-center" width="15%">{{ __('Starting at') }}</th>
                    <th class="text-center" width="15%">{{ __('Ending at') }}</th>
                    <th class="text-center" width="15%">{{ __('Last updated') }}</th>
                    <th class="text-center" width="15%">{{ __('Action') }}</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($records as $row)
                    @if(isset($row->product->title))
                      <tr>
                        <td>
                          <x-admin.status-switcher :id="$row->id" :value="$row->status" />
                        </td>
                        <td><a href="{{ route('seller.daily.deals.edit', ['deal' => $row->id]) }}"> {{ isset($row->product->product_translates) ? $row->product->product_translates->title : $row->product->title }}</a></td>
                        <td class="text-center">{{ $row->discount }}%</td>
                        <td class="text-center">{{ $row->starting_at }}</td>
                        <td class="text-center">{{ $row->ending_at }}</td>
                        <td class="text-center">{{ isset($row->updated_at) ? date_format($row->updated_at,"d/m/Y H:i:s") : '' }}</td>
                        <td class="text-center">
                          <x-admin.edit-button :url="route('seller.daily.deals.edit', ['deal' => $row->id])" />
                          <x-admin.delete-button :url="route('seller.daily.deals.delete', ['deal' => $row->id])" :title="$row->title ?: $row->product->title" />
                          @if (!$row->is_approved)
                          <button type="button" class="btn btn-danger btn-sm mb-1">{{ __('Waiting for approval') }}</button>
                          @endif
                        </td>
                      </tr>
                     @endif
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
        { 'bSortable': true, 'aTargets': [ 2 ], 'name': 'discount' },
        { 'bSortable': true, 'aTargets': [ 3 ], 'name': 'start_date' },
        { 'bSortable': true, 'aTargets': [ 4 ], 'name': 'end_date' },
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
  <x-admin.status-update-js :url="route('seller.daily.deals.update.status')" />
@endpush
