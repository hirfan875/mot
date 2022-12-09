@extends('admin.layouts.app')

@section('content')
    {{--<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <style>
        /* Style to reverse the caret icon from pointing downwards to upwards */
        .caret.caret-up {
            border-top-width: 0;
            border-bottom: 4px solid #fff;
        }

    .hover {
        position:relative;
        top:5px;
        left:5px;
    }

    .tooltip {
       padding: 10px;
      top:1px;
      background-color:black;
      color:white;
      border-radius:5px;
      opacity:0;
      position:absolute;
      -webkit-transition: opacity 0.5s;
      -moz-transition:  opacity 0.5s;
      -ms-transition: opacity 0.5s;
      -o-transition:  opacity 0.5s;
      transition:  opacity 0.5s;
    }

    .hover:hover .tooltip {
        opacity:1;
    }
    </style>--}}
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
                        </div>
                        <div class="card-header">
                            <form action="{{route('admin.products.import')}}" method="POST"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <label
                                            for="products-excel-sheet">{{__('Select products list (only excel format accepted)')}}</label>
                                        <input type="file" name="products-excel-sheet" id="products-excel-sheet"
                                               class="form-control" required>
                                        @error('products-excel-sheet')
                                        <span class="invalid-feedback d-block"
                                              role="alert"> <strong>{{ __($message) }}</strong> </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label for="store_id">{{__('Select Store')}}</label>
                                        <select name="store_id" id="store_id" class="form-control" required>
                                            <option value="">{{__('Select Store')}}</option>
                                            @foreach($stores as $store)
                                                <option value="{{$store->id}}">{{$store->name}}</option>
                                            @endforeach
                                        </select>
                                        @error('store_id')
                                        <span class="invalid-feedback d-block"
                                              role="alert"> <strong>{{ __($message) }}</strong> </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                    </div>
                                </div>
                                @if (Session::has('failures'))
                                    <table class="table table-danger mt-2">
                                        <tr>
                                            <th>{{__('Row')}}</th>
                                            <th>{{__('Attribute')}}</th>
                                            <th>{{__('Error')}}</th>
                                            <th>{{__('Value')}}</th>
                                        </tr>
                                        @foreach(session()->get('failures') as $validation)
                                            <tr>
                                                <td>{{$validation->row() ?? ''}}</td>
                                                <td>{{$validation->attribute() ?? ''}}</td>
                                                <td>
                                                    <ul class="list-unstyled">
                                                        @foreach($validation->errors() as $e)
                                                            <li>{{$e ?? '' }}</li>
                                                        @endforeach
                                                    </ul>
                                                </td>
                                                <td>{{$validation->values()[$validation->attribute()]}}</td>
                                            </tr>
                                        @endforeach
                                    </table>
                                @endif
                                <br>
                                <button class="btn btn-success">{{__('Import Products')}}</button>
                                <a class="btn btn-warning"
                                   href="{{route("admin.products.download-sample-excel")}}">{{ __('Download Sample File') }}</a>
                            </form>
                            <hr>
                            <form action="{{route('admin.import-images-zip')}}" method="POST"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="products-excel-sheet">{{__('Import images ZIP file')}}</label>
                                        <input type="file" name="products-images" id="products-images"
                                               class="form-control" required>
                                        @error('products-images')
                                        <span class="invalid-feedback d-block"
                                              role="alert"> <strong>{{ __($message) }}</strong> </span>
                                        @enderror
                                    </div>
                                </div>
                                <br>
                                <button class="btn btn-success">{{__('Import Images')}}</button>
                            </form>
                        </div>
                        <div class="card-header">
                            <form action="{{route('admin.products')}}" method="GET">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="keyword">{{__('Keyword')}}</label>
                                        <input type="text" id="keyword" name="keyword" class="form-control"
                                               value="{{isset(request()->keyword) ? request()->keyword : null}}">
                                    </div>
                                    @if(isset($categories))
                                        <div class="col-md-3">
                                            <label for="categories">{{__('Select Category')}}</label>
                                            <select name="categories" id="categories" class="form-control">
                                                <option value="">{{__('Select Category')}}</option>
                                                @foreach($categories as $category)
                                                    <option
                                                        value="{{$category->id}}" {{request()->categories == $category->id ? 'selected' : null}}>{{$category->category_translates ? $category->category_translates->title : $category->title}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                    <div class="col-md-3">
                                        <label for="store_id">{{__('Select Store')}}</label>
                                        <select name="store_id" id="store_id" class="form-control">
                                            <option value="">{{__('Select Store')}}</option>
                                            @foreach($stores as $store)
                                                <option
                                                    value="{{$store->id}}" {{request()->store_id == $store->id ? 'selected' : null}}>{{$store->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="type">{{__('Type')}}</label>
                                        <select name="type" id="type" class="form-control">
                                            <option value="">{{__('Select Type')}}</option>
                                                <option value="{{\App\Models\Product::TYPE_SIMPLE}}" {{request()->type == \App\Models\Product::TYPE_SIMPLE ? 'selected' : null}}>{{ucfirst(\App\Models\Product::TYPE_SIMPLE)}}</option>
                                                <option value="{{\App\Models\Product::TYPE_VARIABLE}}" {{request()->type == \App\Models\Product::TYPE_VARIABLE ? 'selected' : null}}>{{ucfirst(\App\Models\Product::TYPE_VARIABLE)}}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 mt-4">
                                        <button type="submit" class="btn btn-success">{{__('Filter')}}</button>
                                        <a href="{{route('admin.products')}}" class="btn btn-danger">{{__('Reset')}}</a>
                                    </div>
                                </div>
                            </form>
                            <x-admin.add-button :url="route('admin.products.add')"/>
                        </div>
                        <div class="card-body">
                            <!-- alerts -->
                            <x-alert class="alert-success" :status="session('success')"/>
                            <x-alert class="alert-danger" :status="session('error')"/>
                            <table class="table table-responsive-sm" id="datatables2">
                                <thead>
                                <tr>
                                    <th width="3%" class="check">
                                        <div class="dropdown" style="float:left;">
                                            <button class="btn btn-primary dropdown-toggle approve-delete" type="button"
                                                    data-toggle="dropdown">
                                                <!--<i class="fas fa-edit"></i>-->
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a href="#" onclick="confirmAllApproved(); ">Approve</a></li>
                                                <li><a href="#" onclick="confirmAllDelete(); ">Delete</a></li>
                                            </ul>
                                        </div>
                                        <div class="dropdown" style="float:left;"><input type="checkbox"
                                                                                         name="select_all" value="1"
                                                                                         id="example-select-all"></div>

                                    </th>
                                    <th width="5%">{{ __('Status') }}</th>
                                    <th width="20%">{{ __('Title') }}</th>
                                    <th width="15%">{{ __('Tags') }}</th>
                                    <th class="text-center" width="10%">{{ __('Store') }}</th>
                                    <th class="text-center" width="10%">{{ __('SKU') }}</th>
                                    <th class="text-center" width="30%">{{ __('Info') }}</th>
                                    <th class="text-center" width="10%">{{ __('Action') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($records as $row)
                                    <tr>
                                        <td>
                                            <input class="myCheckbox" type="checkbox" name="id[]" value="{{$row->id}}">
                                        </td>
                                        <td>
                                            <x-admin.status-switcher :id="$row->id" :value="$row->status"/>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.products.edit', ['product' => $row->id]) }}">{{ $row->product_translates ? $row->product_translates->title : $row->title }}</a>
                                        </td>
                                        <td>
                                            @if($row->tags->count() > 0)
                                                <div class="hover">
                                                    <i class="fa fa-eye"></i> {{$row->tags[0]['title'] }}
                                                    <div class="tooltip">
                                                        @foreach ($row->tags as $r)
                                                            <li class="tooltiptext">{{ $r->title }}</li>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="text-center"><a
                                                href="{{ route('admin.stores.edit', ['store' => $row->store->id]) }}">{{ $row->store ? $row->store->store_profile_translates ? $row->store->store_profile_translates->name : $row->store->name : '' }}</a>
                                        </td>
                                        <td class="text-center">{{ $row->sku }}</td>
                                        <td class="text-center">
                                            <p><strong>{{__('Type')}}: </strong> {{ $row->type }}</p>
                                            <p><strong>{{__('Price')}}: </strong> {{ currency_format($row->price) }}</p>
                                            <p><strong>{{__('Added By')}}: </strong>
                                                @if($row->created_by == null)
                                                    {{'-'}}
                                                @else
                                                    {{$row->created_by == \App\Models\Product::SELLER ? __('Seller') : __('Admin')}}
                                                    / {{$row->created_user != null ? $row->created_user->name : '-'}}
                                                @endif
                                            </p>
                                            <p><strong>{{__('Last Updated')}}
                                                    : </strong> {{date_format($row->updated_at,"d/m/Y H:i:s")}}</p>
                                        </td>
                                        <td class="text-center">
                                            <x-admin.edit-button
                                                :url="route('admin.products.edit', ['product' => $row->id])"/>
                                            <x-admin.delete-button
                                                :url="route('admin.products.delete', ['product' => $row->id])"
                                                :title="$row->title"/>
                                            @if (!$row->is_approved)
                                            @can('approve-products')
                                                <a href="{{ route('admin.products.approve', ['product' => $row->id]) }}"
                                                   class="btn btn-danger btn-sm mb-1"
                                                   onclick="return window.confirm('{{ __('Are you sure you want to approve :title', ['title' => $row->title.'?']) }}');">{{ __('Approve') }}</a>
                                            @endcan
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    {!! $records->appends(Request::all())->links() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('header')
    <x-admin.datatable-css/>
@endpush

@push('footer')
    <x-admin.datatable-js/>
    <script type="text/javascript" charset="utf-8">
        $(document).ready(function () {

            var table = $('#datatables2').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "searching": false
            });
            /*var table = $('#datatables').DataTable({
                "iDisplayLength": 25,
                "autoWidth": false,
                'columnDefs': [{
                    'targets': 0,
                    'searchable': false,
                    'orderable': false,
                    'className': 'dt-body-center',
                }],
                "language": {
                    "emptyTable": "{{__('No data available in table')}}",
                    "zeroRecords": "{{__('No matching records found')}}",
                    "search": "{{__('Search:')}}"
                },
                'order': [[1, 'asc']]
            });*/
            $(table.table().container()).removeClass('form-inline');
            // Handle click on "Select all" control
            $('#example-select-all').on('click', function () {
                // Get all rows with search applied
                var rows = table.rows({'search': 'applied'}).nodes();
                // Check/uncheck checkboxes for all rows in the table
                $('input[name="id[]"]', rows).prop('checked', this.checked);
            });
            // Handle click on checkbox to set state of "Select all" control
            $('#datatables tbody').on('change', 'input[type="checkbox"]', function () {
                // If checkbox is not checked
                if (!this.checked) {
                    var el = $('#example-select-all').get(0);
                    // If "Select all" control is checked and has 'indeterminate' property
                    if (el && el.checked && ('indeterminate' in el)) {
                        // Set visual state of "Select all" control
                        // as 'indeterminate'
                        el.indeterminate = true;
                    }
                }
            });
            // Handle form submission event
            $('#frm-example').on('click', function (e) {
                var form = this;
                // Iterate over all checkboxes in the table
                table.$('input[type="checkbox"]').each(function () {
                    // If checkbox doesn't exist in DOM
                    if (!$.contains(document, this)) {
                        // If checkbox is checked
                        if (this.checked) {
                            // Create a hidden element
                            $(form).append($('<input>').attr('type', 'hidden').attr('name', this.name).val(this.value));
                        }
                    }
                });
            });
        });

        function confirmIsApproved(isApproved, id) {
            if (isApproved == 0) {
                alert("{{trans('Brand not approved yet.')}}");
                return false;
            }
            if (confirm("{{trans('Are you sure you want to approve this product?')}}")) {
                window.location.href = "{{ url('admin/products/approve') }}/" + id;
            }
            return false;
        }

        function confirmAllApproved() {
            if (confirm("{{trans('Are you sure you want to approve all product?')}}")) {

                var values = $('input[name="id[]"].myCheckbox:checked').map(function () {
                    return $(this).val();
                }).toArray();
                var token = "{{ csrf_token() }}";
                $.ajax({
                    type: 'POST',
                    url: '{{ url("admin/products/approved/all") }}',
                    data: JSON.stringify({_token: token, ids: values}),
                    contentType: 'application/json',
                    success: function (result) {
                        $('#success').html('<div class="alert alert-success alert-dismissible" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Rows Approved Successfuly</div>');
                        setTimeout(function () {// wait for 5 secs(2)
                            location.reload(); // then reload the page.(3)
                        }, 1000);
                    }
                });
            }
            return false;
        }

        function confirmAllDelete() {
            if (confirm("{{trans('Are you sure you want to delete all product?')}}")) {
                var values = $('input[name="id[]"].myCheckbox:checked').map(function () {
                    return $(this).val();
                }).toArray();
                var token = "{{ csrf_token() }}";
                $.ajax({
                    type: 'POST',
                    url: '{{ url("admin/products/deleted/all") }}',
                    data: JSON.stringify({_token: token, ids: values}),
                    contentType: 'application/json',
                    success: function (result) {
                        $('#success').html('<div class="alert alert-success alert-dismissible" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Row Deleted Successfuly</div>');
                        setTimeout(function () {// wait for 5 secs(2)
                            location.reload(); // then reload the page.(3)
                        }, 1000);
                    }
                });
            }
            return false;
        }

    </script>
    <script>
        $(document).ready(function () {
            $(".dropdown").on("hide.bs.dropdown", function () {
                $(".approve-delete").html('');
            });
            $(".dropdown").on("show.bs.dropdown", function () {
                $(".approve-delete").html('');
            });
        });
    </script>
    <!-- update status -->
    <x-admin.status-update-js :url="route('admin.products.update.status')"/>
@endpush
