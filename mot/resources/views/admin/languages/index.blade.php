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
              <x-admin.add-button :url="route('admin.languages.add')" />
            </div>
            <div class="card-body">
              <!-- alerts -->
              <x-alert class="alert-success" :status="session('success')" />
              <x-alert class="alert-danger" :status="session('error')" />
              <table class="table table-responsive-sm" id="datatables">
                <thead>
                  <tr>
                    <th width="5%">{{ __('Status') }}</th>
                    <th width="15%">{{ __('Title') }}</th>
                    <th width="15%">{{ __('Native') }}</th>
                    <th class="text-center" width="15%">{{ __('Code') }}</th>
                    <th class="text-center" width="10%">{{ __('Direction') }}</th>
                    <th class="text-center" width="15%">{{ __('Last updated') }}</th>
                    <th class="text-center" width="25%">{{ __('Action') }}</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($records as $row)
                  <tr>
                    <td>
                      <x-admin.status-switcher :id="$row->id" :value="$row->status" />
                    </td>
                    <td><a href="{{ route('admin.languages.edit', ['language' => $row->id]) }}">{{ $row->title }}</a></td>
                    <td class="text-center">{{ $row->native }}</td>
                    <td class="text-center">{{ $row->code }}</td>
                    <td class="text-center">{{ ($row->direction=='ltr')? __("Left-to-right"):__("Right-to-left") }}</td>
                    <td class="text-center">{{ date_format($row->updated_at,"d/m/Y H:i:s") }}</td>
                    <td class="text-center">
                      <a href="{{ route('admin.translation', ['language' => $row->id]) }}" class="btn btn-primary btn-sm mb-1">{{ __('Translation') }}</a>
                      <x-admin.set-default-button :url="route('admin.languages.set.default', ['language' => $row->id])" :default="$row->is_default" :title="$row->title" />
                      <x-admin.edit-button :url="route('admin.languages.edit', ['language' => $row->id])" />
                      @if ( $row->is_default != 'Yes' )
                      <x-admin.delete-button :url="route('admin.languages.delete', ['language' => $row->id])" :title="$row->title" />
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
        { 'bSortable': true, 'aTargets': [ 2 ], 'name': 'code' },
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
  <x-admin.status-update-js :url="route('admin.languages.update.status')" />
@endpush
