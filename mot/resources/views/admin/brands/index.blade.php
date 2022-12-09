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
              {{ $title }}
              <x-admin.add-button :url="route('admin.brands.add')" />
              <x-admin.sorting-button :url="route('admin.brands.sorting')" :records="$records" />
            </div>
            <div class="card-body">
              <!-- alerts -->
              <x-alert class="alert-success" :status="session('success')" />
              <x-alert class="alert-danger" :status="session('error')" />
              <table class="table table-responsive-sm" id="datatables">
                <thead>
                  <tr>
                    <th width="5%">{{ __('Status') }}</th>
                    <th width="50%">{{ __('Title') }}</th>
                    <th class="text-center" width="15%">{{ __('Image') }}</th>
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
                    <td><a href="{{ route('admin.brands.edit', ['brand' => $row->id]) }}">{{ $row->brand_translates ? $row->brand_translates->title : $row->title }}</a></td>
                    <td class="text-center">
                      <x-admin.image :file="$row->image" :thumbnail="$row->getMedia('image', 'thumbnail')" />
                    </td>
                    <td class="text-center">{{ date_format($row->updated_at,"d/m/Y H:i:s") }}</td>
                    <td class="text-center">
                      <x-admin.edit-button :url="route('admin.brands.edit', ['brand' => $row->id])" />
                      <x-admin.delete-button :url="route('admin.brands.delete', ['brand' => $row->id])" :title="$row->title" />
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
        { 'bSortable': false, 'aTargets': [ 2 ], 'name': 'image' },
        { 'bSortable': true, 'aTargets': [ 3 ], 'name': 'updated_at' },
        { 'bSortable': false, 'aTargets': [ 4 ], 'name': 'action' }
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
  <x-admin.status-update-js :url="route('admin.brands.update.status')" />
@endpush
