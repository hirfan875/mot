@extends('admin.layouts.app')

@section('content')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.attributes') }}">{{$attribute->attribute_translates ? $attribute->attribute_translates->title : $attribute->title}}</a></li>
    <li class="breadcrumb-item active">{{ $title }}</li>
  </ol>
  <div class="container-fluid">
    <div class="animated fadeIn">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              {{ $title }}
              <x-admin.back-button :url="route('admin.attributes')" />
              <x-admin.add-button :url="route('admin.attributes.options.add', ['attribute' => $attribute->id])" />
              <x-admin.sorting-button :url="route('admin.attributes.options.sorting', ['attribute' => $attribute->id])" :records="$records" />
            </div>
            <div class="card-body">
              <!-- alerts -->
              <x-alert class="alert-success" :status="session('success')" />
              <x-alert class="alert-danger" :status="session('error')" />
              <table class="table table-responsive-sm" id="datatables">
                <thead>
                  <tr>
                    <th width="64%">{{ __('Title') }}</th>
                    <th class="text-center" width="18%">{{ __('Last updated') }}</th>
                    <th class="text-center" width="18%">{{ __('Action') }}</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($records as $row)
                  <tr>
                    <td>
                      <a href="{{ route('admin.attributes.options.edit', ['attribute' => $attribute->id, 'option' => $row->id]) }}">{{ $row->attribute_translates ? $row->attribute_translates->title : $row->title }}</a>
                    </td>
                    <td class="text-center">{{ date_format($row->updated_at,"d/m/Y H:i:s") }}</td>
                    <td class="text-center">
                      <x-admin.edit-button :url="route('admin.attributes.options.edit', ['attribute' => $attribute->id, 'option' => $row->id])" />
                      <x-admin.delete-button :url="route('admin.attributes.delete', ['attribute' => $row->id])" :title="$row->title" />
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
        { 'bSortable': true, 'aTargets': [ 0 ], 'name': 'title' },
        { 'bSortable': true, 'aTargets': [ 1 ], 'name': 'updated_at' },
        { 'bSortable': false, 'aTargets': [ 2 ], 'name': 'action' }
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
