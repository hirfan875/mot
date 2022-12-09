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
                    <div class="card-header"> {{ $title }} 
                    
                    </div>
                    <div class="card-body">
<!--                        <form action="{{route('admin.trendyol.categories')}}" method="GET">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="keyword">{{__('Search keyword')}}</label>
                                        <input type="text" id="keyword" name="keyword" class="form-control"
                                               value="{{isset(request()->keyword) ? request()->keyword : null}}">
                                    </div>
                                    <div class="col-md-2 mt-4">
                                        <button type="submit" class="btn btn-success">{{__('Search')}}</button>
                                        <a href="{{route('admin.trendyol.categories')}}" class="btn btn-danger">{{__('Reset')}}</a>
                                    </div>
                                </div>
                            </form>-->
                        <!-- alerts -->
                        <x-alert class="alert-success" :status="session('success')" />
                        <x-alert class="alert-danger" :status="session('error')" />
                         
                        <table class="table table-responsive-sm" id="datatables">
                            <thead>
                                <tr>
                                    <th width="47%">{{ __('Title') }}</th>
                                    <th class="text-center" width="15%">{{ __('Id') }}</th>
                                    <th class="text-center" width="15%">{{ __('Assigned category') }}</th>
                                    <th class="text-center" width="15%">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($records as $row)
                                <tr>
                                    <td>{{ $row->title }}</td>
                                    <td class="text-center">{{ $row->id }}</td>
                                    <th class="text-center" width="15%">{{ isset($row->categoriesAssign[0]) ? $row->categoriesAssign[0]->title : '' }}</th>
                                    <td class="text-center">
                                       
                                        <a href="#asignCategoryModal" data-toggle="modal" data-target="#asignCategoryModal" data-url="{{ route('admin.trendyol.categories.assign', ['trendyol' => $row->id]) }}" class="btn btn-outline-primary btn-sm mb-1">{{ __('Assign Category') }}</a>
                                        
                                    </td>
                                </tr>
                                @include('admin.trendyol-categories.subcategories-list', ['subcategories' => $row->childrenRecursive, 'level' => 1])
                                @endforeach
                            </tbody>
                        </table>
                        {!! $records->appends(Request::all())->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Order Overview Modal -->
  <div class="modal fade" id="asignCategoryModal" tabindex="-1" aria-labelledby="asignCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content" id="load_asign_category"></div>
    </div>
  </div>
@endsection

@push('header')
<x-admin.datatable-css />
@endpush

@push('footer')
<x-admin.datatable-js />

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript" charset="utf-8">
    $(document).ready(function () {

        var table = $('#datatables').DataTable({
            "iDisplayLength": 50,
            "order": [],
            "autoWidth": false,
            "paging":false,
            "info":false,
            "aoColumnDefs": [
                {'bSortable': true, 'aTargets': [0], 'name': 'title'},
                {'bSortable': true, 'aTargets': [1], 'name': 'id'},
                {'bSortable': true, 'aTargets': [2], 'name': 'assign'},
                {'bSortable': false, 'aTargets': [3], 'name': 'action'}
            ],
            "language": {
                "emptyTable": "{{__('No data available in table')}}",
                "zeroRecords": "{{__('No matching records found')}}",
                "search": "{{__('Search:')}}"
            }
        });
        $(table.table().container()).removeClass('form-inline');
    });
    
    $('#asignCategoryModal').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget);
      var url = button.data('url');
      $('#spinner_box').show();

      $("#load_asign_category" ).load(url, function () {
        $('#spinner_box').hide();
      });
    });
    
    $(document).ready(function() {
        $('.js-basic-single').select2();
    });
</script>

@endpush
