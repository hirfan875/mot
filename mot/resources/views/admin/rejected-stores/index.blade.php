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
              <a class="btn btn-primary btn-sm pull-right ml-2"  href="{{route('admin.pending.stores')}}">{{ __('Back') }}</a>
            </div>
            <div class="card-body">
              <!-- alerts -->
              <x-alert class="alert-success" :status="session('success')" />
              <x-alert class="alert-danger" :status="session('error')" />
              <table class="table table-responsive-sm" id="datatables">
                <thead>
                  <tr>
                    <th width="24%">{{ __('Name') }}</th>
                    <th class="text-center" width="13%">{{ __('Phone') }}</th>
                    <th class="text-center" width="15%">{{ __('Country') }}</th>
                    <th class="text-center" width="13%">{{ __('Type') }}</th>
                    <th class="text-center" width="15%">{{ __('Last updated') }}</th>
                    <th class="text-center" width="20%">{{ __('Action') }}</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($records as $row)
                  <tr>
                    <td><a href="{{ route('admin.stores.edit', ['store' => $row->id]) }}">{{ $row->store_profile_translates ? $row->store_profile_translates->name : $row->name }}</a></td>
                    <td class="text-center">{{ $row->phone }}</td>
                    <td class="text-center">{{ $row->country ? $row->country->title : '' }}</td>
                    <td class="text-center">{{ $row->display_type }}</td>
                    <td class="text-center">{{ date_format($row->updated_at,"d/m/Y H:i:s") }}</td>
                    <td class="text-center">
                      <x-admin.edit-button :url="route('admin.stores.edit', ['store' => $row->id])" />
                      <x-admin.delete-button :url="route('admin.stores.delete', ['store' => $row->id])" :title="$row->name" />
                        <a href="{{ route('admin.stores.profile', ['store' => $row->id]) }}" class="btn btn-outline-warning btn-sm mb-1 text-dark">{{__('Profile')}}</a>
                    @if($row->isAbleToApprove())
                         <a href="{{ route('admin.stores.approve', ['store' => $row->id]) }}" class="btn btn-success btn-sm mb-1" onclick="return window.confirm('{{ __('Are you sure you want to approve this store?' ) }}');">{{ __('Approve') }}</a>
                      @else
                            <a href="#" class="btn btn-outline-success btn-sm mb-1" onclick="return window.confirm('{{$row->getApprovalValidationMessage('\n')}}');">{{ __('Approve') }}</a>
                      @endif
                      @if(!$row->isAbleToCreateMerchant())
                        <a href="#" class="btn btn-outline-danger btn-sm mb-1" onclick="return window.confirm('{{ __('Iyzico Checklist Not Complete') }}');" style="background: transparent !important; color: red !important;">{{ __('Request SubMerchant') }}</a>
                      @endif
                      @if($row->isAbleToCreateMerchant() && !$row->hasSubMerchantKey())
                        <a href="{{ route('admin.stores.request-submerchant', ['store' => $row->id]) }}" class="btn btn-outline-success btn-sm mb-1" disabled="true" onclick="return window.confirm('{{ __('Are you sure you want to create submerchant of :title', ['title' => $row->name.'?']) }}');">{{ __('Request SubMerchant') }}</a>
                      @endif
                      <!--<a href="{{ route('admin.stores.reject', ['store' => $row->id]) }}" class="btn btn-danger btn-sm mb-1" style="background: transparent !important; color: red !important; border: red solid 1px !important; " onclick="return window.confirm('{{ __('Are you sure you want to reject :title', ['title' => $row->name.'?']) }}');">{{ __('Reject') }}</a>-->
                      <a href="{{ route('seller.login') }}" class="btn btn-info btn-sm mb-1" style="background: transparent !important; color: blueviolet !important; border: blueviolet solid 1px !important; " target="_blank" >{{ __('login as seller') }}</a>
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
        { 'bSortable': true, 'aTargets': [ 0 ], 'name': 'name' },
        { 'bSortable': false, 'aTargets': [ 1 ], 'name': 'phone' },
        { 'bSortable': true, 'aTargets': [ 2 ], 'name': 'country' },
        { 'bSortable': true, 'aTargets': [ 3 ], 'name': 'type' },
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
@endpush
