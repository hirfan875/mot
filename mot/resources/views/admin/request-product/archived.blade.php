@extends('admin.layouts.app')

@section('content')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.contact.inquiries') }}">{{ __('Contact Inquiries') }}</a></li>
    <li class="breadcrumb-item active">{{ $title }}</li>
  </ol>
  <div class="container-fluid">
    <div class="animated fadeIn">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              {{ $title }}
              <x-admin.back-button :url="route('admin.contact.inquiries')" />
            </div>
            <div class="card-body">
              <!-- alerts -->
              <x-alert class="alert-success" :status="session('success')" />
              <x-alert class="alert-danger" :status="session('error')" />
              <table class="table table-responsive-sm" id="datatables">
                <thead>
                  <tr>
                    <th class="text-center" width="7%">{{ __('ID') }}</th>
                    <th width="17%">{{ __('Name') }}</th>
                    <th width="22%">{{ __('Email') }}</th>
                    <th width="14%">{{ __('Phone') }}</th>
                    <th class="text-center" width="10%">{{ __('Status') }}</th>
                    <th class="text-center" width="15%">{{ __('Created at') }}</th>
                    <th class="text-center" width="15%">{{ __('Action') }}</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($records as $row)
                  <tr>
                    <td class="text-center">{{ $row->id }}</td>
                    <td>{{ $row->name }}</td>
                    <td>{{ $row->email }}</td>
                    <td>{{ $row->phone }}</td>
                    <td class="text-center">
                      @if ( $row->status == 2 )
                        {{ __('Viewed') }}
                      @else
                        {{ __('New') }}
                      @endif
                    </td>
                    <td class="text-center">{{ $row->created_at }}</td>
                    <td class="text-center">
                      <x-admin.detail-button :url="route('admin.contact.inquiries.view.archived.detail', ['inquiry' => $row->id])" />
                      <x-admin.delete-button :url="route('admin.contact.inquiries.delete', ['inquiry' => $row->id])" :title="'Inquiry #'.$row->id" />
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
        { 'bSortable': true, 'aTargets': [ 0 ], 'name': 'id' },
        { 'bSortable': true, 'aTargets': [ 1 ], 'name': 'name' },
        { 'bSortable': true, 'aTargets': [ 2 ], 'name': 'email' },
        { 'bSortable': false, 'aTargets': [ 3 ], 'name': 'phone' },
        { 'bSortable': true, 'aTargets': [ 4 ], 'name': 'status' },
        { 'bSortable': true, 'aTargets': [ 5 ], 'name': 'created_at' },
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
@endpush
