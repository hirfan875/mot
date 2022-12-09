@extends('admin.layouts.app')

@section('content')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.trending.products') }}">{{ __($section_title) }}</a></li>
    <li class="breadcrumb-item active">{{ __($title) }}</li>
  </ol>
  <div class="container-fluid">
    <div class="animated fadeIn">
      <div class="row">
        <div class="col-lg-6">
          <div class="card">
            <div class="card-header">
              {{ __($title) }}
              <x-admin.back-button :url="route('admin.trending.products')" />
            </div>
            <div class="card-body">
              <!-- alerts -->
              <x-alert class="alert-success" :status="session('success')" />
              <x-alert class="alert-danger" :status="session('error')" />
              <form action="{{ route('admin.trending.products.edit', ['item' => $row->id]) }}" method="POST" id="edit_form">
                @csrf
                <div class="form-group">
                  <label for="title">{{ __('Title') }}</label>
                  <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $row->title) }}">
                  @error('title')
                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                  @enderror
                </div>
                @php
                  $type = old('type', $row->type);
                @endphp
                <div class="form-group">
                  <label>{{ __('Display Type') }}</label><br>
                  <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="type_product" name="type" class="custom-control-input" value="allProducts" @if ($type === 'allProducts') checked @endif onchange="selectType(this.value)" required>
                    <label class="custom-control-label" for="type_product">{{ __('All Products') }}</label>
                  </div>
                  <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="type_category" name="type" class="custom-control-input" value="category" @if ($type === 'category') checked @endif onchange="selectType(this.value)" required>
                    <label class="custom-control-label" for="type_category">{{ __('By Category') }}</label>
                  </div>
                  @error('type')
                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                  @enderror
                </div>
                <div class="form-group" id="categories_box" style="display: {{ $type === 'category' ? 'block' : 'none' }}">
                  <label for="category_id">{{ __('Category') }}</label>
                  <select name="category_id" id="category_id" class="custom-select select2" required>
                    <option value="">--{{ __('select') }}--</option>
                    @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @if ($row->category_id == $category->id) selected @endif>{{ $category->title }}</option>
                    @if ($category->subcategories)
                    @foreach ($category->subcategories as $subcategory)
                    <option value="{{ $subcategory->id }}" @if ($row->category_id == $subcategory->id) selected @endif>&nbsp;&nbsp;&nbsp;&nbsp;{{ $subcategory->title }}</option>
                    @endforeach
                    @endif
                    @endforeach
                  </select>
                  @error('category_id')
                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                  @enderror
                </div>
                @php
                  $products_type = old('products_type', $row->products_type);
                @endphp
                <div class="form-group">
                  <label>{{ __('Products Type') }}</label><br>
                  <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="products_type_best_selling" name="products_type" class="custom-control-input" value="bestSelling" @if ($products_type === 'bestSelling') checked @endif onchange="selectProductsType(this.value)">
                    <label class="custom-control-label" for="products_type_best_selling">{{ __('Best Selling') }}</label>
                  </div>
                  <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="products_type_tag" name="products_type" class="custom-control-input" value="tag" @if ($products_type === 'tag') checked @endif onchange="selectProductsType(this.value)">
                    <label class="custom-control-label" for="products_type_tag">{{ __('By Tag') }}</label>
                  </div>
                </div>
                <div class="form-group" id="tags_box" style="display: {{ $products_type === 'tag' ? 'block' : 'none' }}">
                  <label for="tag_id">{{ __('Tag') }}</label>
                  <select name="tag_id" id="tag_id" class="custom-select select2" required>
                    <option value="">--{{ __('select') }}--</option>
                    @foreach ($tags as $tag)
                    <option value="{{ $tag->id }}" @if ($row->tag_id == $tag->id) selected @endif>{{ $tag->title }}</option>
                    @endforeach
                  </select>
                  @error('tag_id')
                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="view_all_url">{{ __('View all URL') }}</label>
                  <input type="url" name="view_all_url" id="view_all_url" class="form-control" value="{{ old('view_all_url', $row->view_all_url) }}">
                  @error('url')
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
    });
    function selectType(type){
      if (type === 'category') {
        $('#categories_box').show();
        return;
      }

      $('#categories_box').hide();
    }
    function selectProductsType(type){
      if (type === 'tag') {
        $('#tags_box').show();
        return;
      }

      $('#tags_box').hide();
    }
  </script>
@endpush
