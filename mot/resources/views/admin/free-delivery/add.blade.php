@extends('admin.layouts.app')

@section('content')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.free.delivery') }}">{{ __($section_title) }}</a></li>
    <li class="breadcrumb-item active">{{ __($title) }}</li>
  </ol>
  <div class="container-fluid">
    <div class="animated fadeIn">
      <div class="row">
        <div class="col-lg-8">
          <form action="{{ route('admin.free.delivery.add') }}" method="POST" id="add_form">
            @csrf
            <div class="card">
              <div class="card-header">
                {{ __($title) }}
                <x-admin.back-button :url="route('admin.free.delivery')" />
              </div>
              <div class="card-body">
                <!-- alerts -->
                <x-alert class="alert-success" :status="session('success')" />
                <x-alert class="alert-danger" :status="session('error')" />
                <div class="form-group">
                  <label for="products">{{ __('Choose Products') }}</label>
                  <select name="products[]" id="products" class="select2-ajax-products" multiple>
                  </select>
                  @error('products')
                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                  @enderror
                </div>
                <!-- submit button -->
                <div class="text-center">
                  <x-admin.publish-button />
                </div>
              </div>
            </div>
          </form>
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
      $("#add_form").validate();
      $(".select2-ajax-products").select2({
        width: '100%',
        ajax: {
          url: '{{ route('admin.api.products.select2') }}',
          data: function (params) {
            var query = {
              keyword: params.term,
              free_delivery: 0
            }

            return query;
          }
        }
      });
    });
  </script>
@endpush