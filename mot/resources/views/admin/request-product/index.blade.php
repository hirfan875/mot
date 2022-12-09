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
              <!--<a class="btn btn-warning btn-sm pull-right ml-2" href="{{ route('admin.request.products.view.archived') }}">{{ __('View Archived') }}</a>-->
            </div>
            <div class="card-body">
              <!-- alerts -->
              <x-alert class="alert-success" :status="session('success')" />
              <x-alert class="alert-danger" :status="session('error')" />
              @error('bulk_action')
              <div class="alert alert-danger">{{ __($message) }}</div>
              @enderror
              @error('inquiries')
              <div class="alert alert-danger">{{ __($message) }}</div>
              @enderror
              <form action="{{ route('admin.request.products.bulk.actions') }}" method="post">
                @csrf
<!--                <div class="form-row align-items-center mb-3">
                  <div class="col-auto my-1">
                    <select class="custom-select mr-sm-2" name="bulk_action" id="bulk_action">
                      <option value="" selected>--{{ __('Bulk actions') }}--</option>
                      <option value="viewed">{{ __('Mark as Viewed') }}</option>
                      <option value="archive">{{ __('Archive') }}</option>
                      <option value="delete">{{ __('Delete') }}</option>
                    </select>
                  </div>
                  <div class="col-auto my-1">
                    <button type="submit" class="btn btn-primary">{{ __('Apply') }}</button>
                  </div>
                </div>-->
                <table class="table table-responsive-sm" id="datatables">
                  <thead>
                    <tr>
<!--                      <th>
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" id="checkAll">
                          <label class="custom-control-label" for="checkAll"></label>
                        </div>
                      </th>-->
                      <th class="text-center" width="7%">{{ __('ID') }}</th>
                      <th width="15%">{{ __('Name') }}</th>
                      <th width="21%">{{ __('Email') }}</th>
                      <th width="13%">{{ __('Phone') }}</th>
                      <th class="text-center" width="9%">{{ __('Message') }}</th>
                      <th class="text-center" width="14%">{{ __('Created at') }}</th>
                      <th class="text-center" width="20%">{{ __('Action') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($records as $row)
                    <tr>
<!--                      <td>
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" name="inquiries[]" id="inquiry_{{ $row->id }}" value="{{ $row->id }}">
                          <label class="custom-control-label" for="inquiry_{{ $row->id }}"></label>
                        </div>
                      </td>-->
                      <td class="text-center">{{ $row->id }}</td>
                      <td>{{ $row->name }}</td>
                      <td>{{ $row->email }}</td>
                      <td>{{ $row->phone }}</td>
                      <td class="text-center">
                          {{ $row->comment }}
                      </td>
                      <td class="text-center">{{ date_format($row->created_at,"d/m/Y H:i:s") }}</td>
                      <td class="text-center">
                        <x-admin.detail-button :url="route('admin.request.products.detail', ['requestProduct' => $row->id])" />
                        <!--<a href="{{ route('admin.request.products.archive', ['requestProduct' => $row->id]) }}" class="btn btn-danger btn-sm mb-1" onclick="return window.confirm('{{ __('Are you sure you want to archive requestProduct # :id', ['id' => $row->id.'?']) }}');">{{ __('Archive') }}</a>-->
                        <x-admin.delete-button :url="route('admin.request.products.delete', ['requestProduct' => $row->id])" :title="'requestProduct #'.$row->id" />
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </form>
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
        { 'bSortable': false, 'aTargets': [ 0 ], 'name': 'checkbox' },
        { 'bSortable': true, 'aTargets': [ 1 ], 'name': 'id' },
        { 'bSortable': true, 'aTargets': [ 2 ], 'name': 'name' },
        { 'bSortable': true, 'aTargets': [ 3 ], 'name': 'email' },
        { 'bSortable': false, 'aTargets': [ 4 ], 'name': 'phone' },
        { 'bSortable': true, 'aTargets': [ 5 ], 'name': 'status' },
        { 'bSortable': true, 'aTargets': [ 6 ], 'name': 'created_at' },
        { 'bSortable': false, 'aTargets': [ 7 ], 'name': 'action' }
        ],
          "language": {
              "emptyTable": "{{__('No data available in table')}}",
              "zeroRecords": "{{__('No matching records found')}}",
              "search":      "{{__('Search:')}}"
          }
      });
      $( table.table().container() ).removeClass( 'form-inline' );
      $("#checkAll").click(function(){
        $('input:checkbox').not(this).prop('checked', this.checked);
      });
    });
  </script>
@endpush
