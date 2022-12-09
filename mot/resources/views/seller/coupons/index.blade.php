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
              <x-admin.add-button :url="route('seller.coupons.add')" />
            </div>
            <div class="card-body">
              <!-- alerts -->
              <x-alert class="alert-success" :status="session('success')" />
              <x-alert class="alert-danger" :status="session('error')" />
              <table class="table table-responsive-sm" id="datatables">
                <thead>
                  <tr>
                    <th class="text-center" width="12%">{{ __('Coupon') }}</th>
                    <th class="text-center" width="12%">{{ __('Type') }}</th>
                    <th class="text-center" width="10%">{{ __('Discount') }}</th>
                    <th class="text-center" width="10%">{{ __('Start Date') }}</th>
                    <th class="text-center" width="10%">{{ __('End Date') }}</th>
                    <th class="text-center" width="10%">{{ __('Total Usage') }}</th>
                    <th class="text-center" width="13%">{{ __('Applies to') }}</th>
                    <th class="text-center" width="13%">{{ __('Created at') }}</th>
                    <th class="text-center" width="10%">{{ __('Action') }}</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($records as $row)
                  <tr>
                    <td class="text-center">{{ $row->coupon_code }}</td>
                    <td class="text-center">{{ $row->type }}</td>
                    <td class="text-center">{{ $row->discount }}</td>
                    <td class="text-center">{{ $row->display_start_date }}</td>
                    <td class="text-center">{{ $row->display_end_date }}</td>
                    <td class="text-center">{{ $row->display_usage_limit }}</td>
                    <td class="text-center">{{ $row->display_applies_to }}</td>
                    <td class="text-center">{{ isset($row->created_at) ? date_format($row->created_at,"d/m/Y H:i:s") : ''  }}</td>
                    <td class="text-center">
                      <x-admin.edit-button :url="route('seller.coupons.edit', ['coupon' => $row->id])" />
                      <x-admin.delete-button :url="route('seller.coupons.delete', ['coupon' => $row->id])" :title="$row->title" />
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
        { 'bSortable': true, 'aTargets': [ 0 ], 'name': 'coupon_code' },
        { 'bSortable': true, 'aTargets': [ 1 ], 'name': 'type' },
        { 'bSortable': true, 'aTargets': [ 2 ], 'name': 'discount' },
        { 'bSortable': true, 'aTargets': [ 3 ], 'name': 'start_date' },
        { 'bSortable': true, 'aTargets': [ 4 ], 'name': 'end_date' },
        { 'bSortable': true, 'aTargets': [ 5 ], 'name': 'usage_limit' },
        { 'bSortable': true, 'aTargets': [ 6 ], 'name': 'applies_to' },
        { 'bSortable': true, 'aTargets': [ 7 ], 'name': 'created_at' },
        { 'bSortable': false, 'aTargets': [ 8 ], 'name': 'action' }
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
