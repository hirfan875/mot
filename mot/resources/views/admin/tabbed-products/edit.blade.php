@extends('admin.layouts.app')

@section('content')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.tabbed.products') }}">{{ __($section_title) }}</a></li>
    <li class="breadcrumb-item active">{{ __($title) }}</li>
  </ol>
  <div class="container-fluid">
    <div class="animated fadeIn">
      <div class="row">
        <div class="col-lg-6">
          <div class="card">
            <div class="card-header">
              {{ __($title) }}
              <x-admin.back-button :url="route('admin.tabbed.products')" />
            </div>
            <div class="card-body">
              <!-- alerts -->
              <x-alert class="alert-success" :status="session('success')" />
              <x-alert class="alert-danger" :status="session('error')" />
              <form action="{{ route('admin.tabbed.products.edit', ['item' => $row->id]) }}" method="POST" id="edit_form">
                @csrf
                <div class="form-group">
                  <label for="title">{{ __('Title') }}</label>
                  <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $row->title) }}" >
                  @error('title')
                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                  @enderror
                </div>
                @php
                  $type = old('type', $row->type);
                @endphp
                <div class="form-group">
                  <label>{{ __('Type') }}</label><br>
                  <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="type_category" name="type" class="custom-control-input" value="category" @if ($type === 'category') checked @endif onchange="selectType(this.value)">
                    <label class="custom-control-label" for="type_category">{{ __('Category') }}</label>
                  </div>
                  <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="type_product" name="type" class="custom-control-input" value="product" @if ($type === 'product') checked @endif onchange="selectType(this.value)">
                    <label class="custom-control-label" for="type_product">{{ __('Products') }}</label>
                  </div>
                </div>
                <div class="form-group" id="categories_box" style="display: {{ $type === 'category' ? 'block' : 'none' }}">
                  <label for="category_id">{{ __('Category') }}</label>
                  <select name="category_id" id="category_id" class="custom-select select2" >
                    <option value="">--{{ __('select') }}--</option>
                    @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @if ($row->category_id == $category->id) selected @endif>{{$category->category_translates ? $category->category_translates->title : $category->title}}</option>
                    @if ($category->subcategories)
                    @foreach ($category->subcategories as $subcategory)
                    <option value="{{ $subcategory->id }}" @if ($row->category_id == $subcategory->id) selected @endif>&nbsp;&nbsp;&nbsp;&nbsp;{{$subcategory->category_translates ? $subcategory->category_translates->title : $subcategory->title}}</option>
                    @endforeach
                    @endif
                    @endforeach
                  </select>
                  @error('category_id')
                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                  @enderror
                </div>
                <div class="form-group" id="products_box" style="display: {{ $type === 'product' ? 'block' : 'none' }}">
                  <label for="products">{{ __('Products') }}</label>
                  <select name="products[]" id="products" class="custom-select select2-multiple"  multiple>
                    <option value="">--{{ __('select') }}--</option>
                    @foreach ($products as $product)
                    <option value="{{ $product->id }}" @if (in_array($product->id, $section_products)) selected @endif>{{ $product->title }}</option>
                    @endforeach
                  </select>
                  @error('products')
                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                  @enderror
                </div>
                <!-- submit button -->
                <div class="text-center">
                  <x-admin.save-changes-button />
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('header')
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet">
  <link rel="stylesheet" href="//select2.github.io/select2-bootstrap-theme/css/select2-bootstrap.css" rel="stylesheet">
@endpush

@push('footer')
  <x-validation />
  <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
  <script type="text/javascript" charset="utf-8">
    $.fn.select2.defaults.set( "theme", "bootstrap" );
    $(document).ready(function(){
      $("#edit_form").validate();
      $(".select2").select2({
        width: '100%'
      });
      $(".select2-multiple").select2({
        placeholder: 'Choose',
        width: '100%'
      });
    });
    function selectType(type){
      if (type === 'product') {
        $('#products_box').show();
        $('#categories_box').hide();
        return;
      }

      $('#products_box').hide();
      $('#categories_box').show();
    }
  </script>
@endpush
