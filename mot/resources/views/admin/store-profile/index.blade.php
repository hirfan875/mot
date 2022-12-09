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
            </div>
            <div class="card-body">
              <!-- alerts -->
              <x-alert class="alert-success" :status="session('success')" />
              <x-alert class="alert-danger" :status="session('error')" />
              <table class="table table-responsive-sm" id="datatables">
                <thead>
                  <tr>
                    <th width="48%">{{ __('Description') }}</th>
                    <th class="text-center" width="15%">{{ __('Banner') }}</th>
                    <th class="text-center" width="15%">{{ __('Logo') }}</th>
                    <th class="text-center" width="15%">{{ __('Last updated') }}</th>
                    <th class="text-center" width="22%">{{ __('Action') }}</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($records as $row)
                  <tr>
                    <td>{{ Str::limit(strip_tags($store->store_profile_translates ? $store->store_profile_translates->description : $row->description), 200) }}</td>
                    <td class="text-center">
                      <x-admin.image :file="$row->banner" :thumbnail="$row->getMedia('banner', 'thumbnail')" />
                    </td>
                    <td class="text-center">
                      <x-admin.image :file="$row->banner" :thumbnail="$row->getMedia('logo', 'thumbnail')" />
                    </td>
                    <td class="text-center">{{ date_format($row->updated_at,"d/m/Y H:i:s") }}</td>
                    <td class="text-center">
                      <x-admin.edit-button :url="route('admin.stores.profile.edit', ['store' => $store->id, 'item' => $row->id])" />
                      @if ($row->status == 0)
                      <a href="{{ route('admin.stores.profile.approve', ['store' => $store->id, 'item' => $row->id]) }}" class="btn btn-success btn-sm mb-1" onclick="return window.confirm('{{ __('Are you sure you want to approve this record?') }}');">{{ __('Approve') }}</a>
                      <a href="{{ route('admin.stores.profile.reject', ['store' => $store->id, 'item' => $row->id]) }}" class="btn btn-danger btn-sm mb-1" onclick="return window.confirm('{{ __('Are you sure you want to reject record?') }}');">{{ __('Reject') }}</a>
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
        { 'bSortable': false, 'aTargets': [ 0 ], 'name': 'description' },
        { 'bSortable': false, 'aTargets': [ 1 ], 'name': 'banner' },
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
@endpush
