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
              <x-admin.add-button :url="route('admin.sliders.add')" />
              <x-admin.sorting-button :url="route('admin.sliders.sorting')" :records="$records" />
            </div>
            <div class="card-body">
              <!-- alerts -->
              <x-alert class="alert-success" :status="session('success')" />
              <x-alert class="alert-danger" :status="session('error')" />
              <table class="table table-responsive-sm" id="datatables">
                <thead>
                  <tr>
                    <th width="5%">{{ __('Status') }}</th>
                    <th width="29%">{{ __('Title') }}</th>
                    <th class="text-center" width="15%">{{ __('Image') }}</th>
                    <th class="text-center" width="20%">{{ __('Button Text') }}</th>
                    <th class="text-center" width="15%">{{ __('Last updated') }}</th>
                    <th class="text-center" width="16%">{{ __('Action') }}</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($records as $key=>$row)
                  <tr>
                    <td>
                      <x-admin.status-switcher :id="$row->id" :value="$row->status" />
                    </td>
                    <td class="align-middle"><a href="{{ route('admin.sliders.edit', ['slider' => $row->id]) }}">{{__('Slide')}} {{ $key+1 }}</a></td>
                    <td class="align-middle text-center">
                      <x-admin.image :file="$row->image" :thumbnail="$row->getMedia('image', 'thumbnail')" />
                    </td>
                    <td class="align-middle text-center">{{ __($row->button_text) }}</td>
                    <td class="align-middle text-center">{{ date_format($row->updated_at,"d/m/Y H:i:s") }}</td>
                    <td class="align-middle text-center">
                      <x-admin.edit-button :url="route('admin.sliders.edit', ['slider' => $row->id])" />
                      <x-admin.delete-button :url="route('admin.sliders.delete', ['slider' => $row->id])" :title="$row->title" />
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
        { 'bSortable': false, 'aTargets': [ 3 ], 'name': 'button_text' },
        { 'bSortable': true, 'aTargets': [ 4 ], 'name': 'updated_at' },
        { 'bSortable': false, 'aTargets': [ 5 ], 'name': 'action' }
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
  <x-admin.status-update-js :url="route('admin.sliders.update.status')" />
@endpush
