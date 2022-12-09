@extends('seller.layouts.app')

@section('content')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('seller.coupons') }}">{{ __($section_title) }}</a></li>
    <li class="breadcrumb-item active">{{ __($title) }}</li>
  </ol>
  <div class="container-fluid">
    <div class="animated fadeIn">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              {{ __($title) }}
              <x-admin.back-button :url="route('seller.coupons')" />
            </div>
            <div class="card-body">
              <!-- alerts -->
              <x-alert class="alert-success" :status="session('success')" />
              <x-alert class="alert-danger" :status="session('error')" />
              <form action="{{ route('seller.coupons.edit', ['coupon' => $row->id]) }}" method="POST" id="edit_form">
                @csrf
                <input type="hidden" name="store" value="{{ auth()->user()->store_id }}">
                <div class="row">
                  <div class="col-sm-4">
                    <div class="form-group">
                      <label for="coupon_code">{{ __('Coupon Code') }}</label>
                      <input type="text" name="coupon_code" id="coupon_code" class="form-control" value="{{ old('coupon_code', $row->coupon_code) }}">
                      @error('coupon_code')
                        <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                      @enderror
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="form-group">
                      <label for="start_date">{{ __('Start Date') }}</label>
                      <input type="text" name="start_date" id="start_date" class="form-control loaddatepicker" value="{{ old('start_date', $row->display_start_date) }}" autocomplete="off" onkeypress="return false;">
                      @error('start_date')
                      <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                      @enderror
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="form-group">
                      <label for="end_date">{{ __('End Date') }}</label>
                      <input type="text" name="end_date" id="end_date" class="form-control loaddatepicker" value="{{ old('end_date', $row->display_end_date) }}" autocomplete="off" onkeypress="return false;">
                      @error('end_date')
                      <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                      @enderror
                    </div>
                  </div>
                  @php
                    $type = old('type', $row->type);
                  @endphp
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label>{{ __('Discount Type') }}</label><br>
                      <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="type" name="type" class="custom-control-input" value="fixed" @if ( $type === 'fixed' ) checked @endif>
                        <label class="custom-control-label" for="type">{{ __('Fixed Amount') }}</label>
                      </div>
                      <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="type2" name="type" class="custom-control-input" value="percentage" @if ( $type === 'percentage' ) checked @endif>
                        <label class="custom-control-label" for="type2">{{ __('Percentage') }}</label>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="discount">{{ __('Discount') }}</label>
                      <input type="text" name="discount" id="discount" class="form-control number" value="{{ old('discount', $row->discount) }}" min="1" max="100" autocomplete="off">
                      @error('discount')
                      <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                      @enderror
                    </div>
                  </div>
                  @php
                    $usage_limit = old('usage_limit', $row->usage_limit);
                  @endphp
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label>{{ __('Total Usage') }}</label><br>
                      <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="usage_limit" name="usage_limit" class="custom-control-input" value="1" onchange="UsageLimit(this.value)" @if ( $usage_limit == 1 ) checked @endif>
                        <label class="custom-control-label" for="usage_limit">{{ __('Unlimited') }}</label>
                      </div>
                      <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="usage_limit2" name="usage_limit" class="custom-control-input" value="2" onchange="UsageLimit(this.value)" @if ( $usage_limit == 2 ) checked @endif>
                        <label class="custom-control-label" for="usage_limit2">{{ __('Limited') }}</label>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-6" id="limit_box" style="display: {{ $usage_limit == 2 ? 'block' : 'none' }};">
                    <div class="row">
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label for="limit">{{ __('Limit') }}</label>
                          <input type="text" name="limit" id="limit" class="form-control number" value="{{ old('limit', $row->limit_no) }}" autocomplete="off" required>
                          @error('limit')
                          <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                          @enderror
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label for="per_user_limit">{{ __('Per User Limit') }}</label>
                          <input type="text" name="per_user_limit" id="per_user_limit" class="form-control number" value="{{ old('per_user_limit', $row->per_user_limit) }}" autocomplete="off">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                @php
                  $applies_to = old('applies_to', $row->applies_to);
                @endphp
                <div class="form-group">
                  <label>{{ __('Applies To') }}</label><br>
                  <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="applies_to" name="applies_to" class="custom-control-input" value="1" onchange="AppliesTo(this.value)" @if ( $applies_to == 1 ) checked @endif>
                    <label class="custom-control-label" for="applies_to">{{ __('All Products') }}</label>
                  </div>
                  <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="applies_to2" name="applies_to" class="custom-control-input" value="2" onchange="AppliesTo(this.value)" @if ( $applies_to == 2 ) checked @endif>
                    <label class="custom-control-label" for="applies_to2">{{ __('Specific Products') }}</label>
                  </div>
                  <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="applies_to3" name="applies_to" class="custom-control-input" value="3" onchange="AppliesTo(this.value)" @if ( $applies_to == 3 ) checked @endif>
                    <label class="custom-control-label" for="applies_to3">{{ __('Specific Categories') }}</label>
                  </div>
                  <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="applies_to4" name="applies_to" class="custom-control-input" value="4" onchange="AppliesTo(this.value)" @if ( $applies_to == 4 ) checked @endif>
                    <label class="custom-control-label" for="applies_to4">{{ __('Sub Total') }}</label>
                  </div>
                  <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="applies_to5" name="applies_to" class="custom-control-input" value="5" onchange="AppliesTo(this.value)" @if ( $applies_to == 5 ) checked @endif>
                    <label class="custom-control-label" for="applies_to5">{{ __('Shipping') }}</label>
                  </div>
                </div>
                <div id="choose_products_box" style="display: {{ $applies_to == 2 ? 'block' : 'none' }};">
                  <div class="form-group" style="position: relative;">
                    <label for="products">{{ __('Choose Products') }}</label>
                    <select name="products[]" id="products" class="select2-ajax-products" multiple>
                      @foreach ($selected_products as $r)
                      <option value="{{ $r->id }}" selected>{{ $r->title }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                @php
                  $selected_categories = old('categories', $row->categories->pluck('id')->toArray());
                @endphp
                <div class="form-group" id="choose_categories_box" style="display: {{ $applies_to == 3 ? 'block' : 'none' }};">
                  <label for="categories">{{ __('Choose Categories') }}</label>
                  <select name="categories[]" id="categories" class="custom-select select2-multiple" multiple>
                    <option value="">--{{ __('choose') }}--</option>
                    @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @if (in_array($category->id, $selected_categories)) selected @endif>{{ $category->title }}</option>
                    @if ($category->subcategories)
                    @foreach ($category->subcategories as $subcategory)
                    <option value="{{ $subcategory->id }}" @if (in_array($subcategory->id, $selected_categories)) selected @endif>&nbsp;&nbsp;&nbsp;&nbsp;{{ $subcategory->title }}</option>
                    @endforeach
                    @endif
                    @endforeach
                  </select>
                </div>
                <div class="form-group" id="choose_amount_box" style="display: {{ $applies_to == 4 ? 'block' : 'none' }};">
                  <label for="sub_total">{{ __('Minimum Subtotal Limit') }}</label>
                  <input type="text" name="sub_total" id="sub_total" class="form-control number" value="{{ old('sub_total', $row->sub_total) }}">
                  @error('sub_total')
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
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet">
  <link rel="stylesheet" href="//select2.github.io/select2-bootstrap-theme/css/select2-bootstrap.css" rel="stylesheet">
@endpush

@push('footer')
  <x-validation />
  <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
  <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
  <script type="text/javascript" charset="utf-8">
    $.fn.select2.defaults.set( "theme", "bootstrap" );
    $(document).ready(function(){
      $("#edit_form").validate();
      $('.loaddatepicker').datepicker({
        format: "yyyy-mm-dd",
        startDate: new Date(),
        autoclose: true,
        todayHighlight: true,
        orientation: "bottom auto"
      });
      $(".select2-multiple").select2({
        width: '100%'
      });
      $(".select2-ajax-products").select2({
        width: '100%',
        ajax: {
          url: '{{ route('admin.api.products.select2') }}',
          data: function (params) {
            var query = {
              keyword: params.term
            }

            return query;
          }
        }
      });
    });
    function UsageLimit(type) {
      if ( type == 2 ) $('#limit_box').show(); else $('#limit_box').hide();
    }
    function AppliesTo(type) {
      if ( type == 2 ) {
        $('#choose_products_box').show();
        $('#choose_categories_box').hide();
        $('#choose_amount_box').hide();
        return;
      }

      if ( type == 3 ) {
        $('#choose_categories_box').show();
        $('#choose_products_box').hide();
        $('#choose_amount_box').hide();
        return;
      }

      if ( type == 4 ) {
        $('#choose_amount_box').show();
        $('#choose_products_box').hide();
        $('#choose_categories_box').hide();
        return;
      }

      $('#choose_products_box').hide();
      $('#choose_categories_box').hide();
      $('#choose_amount_box').hide();
      return;
    }
  </script>
@endpush
