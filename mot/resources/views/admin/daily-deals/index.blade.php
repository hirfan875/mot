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
            </div>
            <div class="card-body">
              <!-- alerts -->
              <x-alert class="alert-success" :status="session('success')" />
              <x-alert class="alert-danger" :status="session('error')" />
              <table class="table table-responsive-sm" id="datatables">
                <thead>
                  <tr>
                    <th width="5%">{{ __('Status') }}</th>
                    <th width="25%">{{ __('Products') }}</th>
                    <th class="text-center" width="10%">{{ __('Discount') }}</th>
                    <th class="text-center" width="15%">{{ __('Starting at') }}</th>
                    <th class="text-center" width="15%">{{ __('Ending at') }}</th>
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
                    <td>{{ isset($row->product->product_translates) ? $row->product->product_translates->title : $row->product->title }}</td>
                    <td class="text-center">{{ $row->discount }}%</td>
                    <td class="text-center">{{ $row->starting_at }}</td>
                    <td class="text-center">{{ $row->ending_at }}</td>
                    <td class="text-center">{{ date_format($row->updated_at,"d/m/Y H:i:s") }}</td>
                    <td class="text-center">
                      <x-admin.delete-button :url="route('admin.daily.deals.delete', ['deal' => $row->id])" :title="$row->product->title" />
                      @if (!$row->is_approved)
                      <a href="{{ route('admin.daily.deals.approve', ['deal' => $row->id]) }}" class="btn btn-danger btn-sm mb-1" onclick="return window.confirm('{{ __('Are you sure you want to approve :title', ['title' => $row->product->title.'?']) }}');">{{ __('Approve') }}</a>
                      @endif
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
  <x-admin.status-update-js :url="route('admin.daily.deals.update.status')" />
@endpush
