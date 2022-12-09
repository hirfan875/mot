@extends('seller.layouts.app')

@section('content')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('seller.products') }}">{{ __($section_title) }}</a></li>
    <li class="breadcrumb-item active">{{ __($title) }}</li>
</ol>
<div class="container-fluid">
    <div class="animated fadeIn">
        <form action="{{ route('seller.products.add') }}" method="POST" enctype="multipart/form-data" id="add_form">
            @csrf
            <input type="hidden" name="store" id="store" value="{{ auth()->user()->store_id }}">
            <input type="hidden" name="storeName" id="storeName" value="{{ auth()->user()->store->name }}">
            <input type="hidden" name="mot_commission" id="mot_commission" value="">
            <div class="row">
                <div class="col-lg-9">
                    <div class="card">
                        <div class="card-header">
                            {{ __($title) }}
                            <x-admin.back-button :url="route('seller.products')" />
                        </div>
                        <div class="card-body">
                            <!-- alerts -->
                            <x-alert class="alert-success" :status="session('success')" />
                            <x-alert class="alert-danger" :status="session('error')" />
                            <div class="row">
                                @foreach(getLocaleList() as $val)
                                <div class="col-sm-9">
                                    <div class="form-group">
                                        <label for="title">{{ __('Title ('.$val->title.')') }}<sup style="color:red;">*</sup></label>
                                        <input type="text" name="title[{{$val->id}}]" id="title{{$val->id}}" class="form-control" value="{{ old('title[$val->id]') }}"  {{$val->is_default == 'Yes' ? 'required' : ''}} title="{{__('Please fill out this field')}}" autofocus>
                                        @error('title[{{$val->id}}]')
                                        <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                        @enderror
                                    </div>
                                </div>
                                @endforeach
                                @php
                                $type = old('type', 'simple');
                                @endphp
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="type">{{ __('Product Type') }}<sup style="color:red;">*</sup></label>
                                        <select name="type" id="type" class="custom-select" onchange="selectProductType(this.value)">
                                            <option value="simple" @if ( $type === 'simple' ) selected @endif >{{ __('Simple') }}</option>
                                            <option value="variable" @if ( $type === 'variable' ) selected @endif >{{ __('Variable') }}</option>
                                            <option value="bundle" @if ( $type === 'bundle' ) selected @endif >{{ __('Bundle') }}</option>
                                        </select>
                                        @error('type')
                                        <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="sku">{{ __('SKU') }}<sup style="color:red;">*</sup></label>
                                        <input type="text" name="store_sku" id="store_sku" class="form-control" value="{{ old('store_sku') }}">
                                        <input type="hidden" name="sku" id="sku" class="form-control" value="{{ old('sku') }}">
                                        @error('sku')
                                        <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="stock">{{ __('Stock') }}<sup style="color:red;">*</sup></label>
                                        <input type="text" name="stock" id="stock" class="form-control number" value="{{ old('stock') }}" maxlength="4" onfocusout="updateVariations(this.value, 'stock')" @if ($type === 'variable') disabled @endif>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="price">{{ __('Price') }}<sup style="color:red;">*</sup></label>
                                        <input type="text" name="price" id="price" class="form-control number" value="{{ old('price') }}" onfocusout="updateVariations(this.value, 'price')">
                                        @error('price')
                                        <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <p>{{__('Note: weight & volumetric weight required for shipment purposes.')}}</p>
                                </div>
                                <div class="col-sm-3 dimensions" style="display: {{ $type === 'variable' ? 'none' : 'block' }}">
                                    <div class="form-group">
                                        <label for="weight">{{ __('Weight') }}</label>
                                        <input type="text" name="weight" id="weight" class="form-control number" value="{{ old('weight') }}" >
                                    </div>
                                </div>

                                <div class="col-sm-3 dimensions" style="display: {{ $type === 'variable' ? 'none' : 'block' }}">
                                    <div class="form-group">
                                        <label for="length">{{ __('Length') }}</label>
                                        <input type="text" name="length" id="length" class="form-control number" value="{{ old('length') }}" >
                                    </div>
                                </div>
                                <div class="col-sm-3 dimensions" style="display: {{ $type === 'variable' ? 'none' : 'block' }}">
                                    <div class="form-group">
                                        <label for="height">{{ __('Height') }}</label>
                                        <input type="text" name="height" id="height" class="form-control number" value="{{ old('height') }}" >
                                    </div>
                                </div>
                                <div class="col-sm-3 dimensions" style="display: {{ $type === 'variable' ? 'none' : 'block' }}">
                                    <div class="form-group">
                                        <label for="width">{{ __('Width') }}</label>
                                        <input type="text" name="width" id="width" class="form-control number" value="{{ old('width') }}" >
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <a class="btn btn-info" onclick="myFunction()">Advance</a>
                                </div>

                                <div class="col-sm-12" id="advance"  style="display: none;" >
                                    <div class='row'>
                                        @php
                                        $brand = old('brand');
                                        @endphp
                                        <div class="col-sm-8" id="brand_box_main" style="display: {{ $type === 'bundle' ? 'none' : 'block' }}">
                                            <div class="col-sm-6" style="float: left;">
                                                <div class="form-group">
                                                    <label for="brand">{{ __('Create My Own Brand') }}</label>
                                                    <div><label for="yesno">{{__('Yes')}}</label> <input type="radio" onclick="javascript:additionalBrand(0);" name="yesno" id="yesno"> <label for="no"> {{__('No')}}</label> <input type="radio" onclick="javascript:additionalBrand(1);" name="yesno" id="no" checked></div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6"  id="additional_brand" style="display: none; float: left;">
                                                <label for="brand">{{ __('Brand') }}</label>
                                                <div class="form-group" >
                                                    <input type="text" name="additional-brand" id="additional-brand" class="form-control" value="{{ old('additional-brand') }}" >
                                                    @error('additional-brand')
                                                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6" id="brand_box" style="display: block; float: left;">
                                                <div class="form-group">
                                                    <label for="brand">{{ __('Brand') }}</label>
                                                    <select name="brand" id="brand" class="custom-select" >
                                                        <option value="">--{{ __('select') }}--</option>
                                                        @foreach ($brands as $r)
                                                        <option value="{{ $r->id }}" @if ( $brand == $r->id ) selected @endif >{{ $r->brand_translates ? $r->brand_translates->title : $r->title }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('brand')
                                                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        @can('mot-fee')
                                        <div class="col-sm-4"  id="mot_fee_box" >
                                            <div class="form-group">
                                                <label for="mot_fee">{{ __('Mot Fee') }}</label>
                                                <input type="text" name="mot_fee" id="mot_fee" class="form-control number" disabled>
                                            </div>
                                        </div>
                                        @endcan
                                        @php
                                        $discount_type = old('discount_type');
                                        @endphp
                                        <div class="col-sm-4"  id="discount_type_box" style="display: {{ $type === 'variable' ? 'none' : 'block' }}">
                                            <div class="form-group">
                                                <label for="discount_type">{{ __('Discount Type') }}</label>
                                                <select name="discount_type" id="discount_type" class="custom-select" onchange="updateVariations(this.value, 'discount-type')">
                                                    <option value="">--{{ __('select') }}--</option>
                                                    <option value="fixed" @if ( $discount_type === 'fixed' ) selected @endif >{{ __('Fixed') }}</option>
                                                    <option value="percentage" @if ( $discount_type === 'percentage' ) selected @endif >{{ __('Percentage') }}</option>
                                                </select>
                                                @error('discount_type')
                                                <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-4"  id="discount_box" style="display: {{ $type === 'variable' ? 'none' : 'block' }}">
                                            <div class="form-group">
                                                <label for="discount">{{ __('Discount') }}</label>
                                                <input type="text" name="discount" id="discount" class="form-control number" value="{{ old('discount') }}" onfocusout="updateVariations(this.value, 'discount')">
                                                @error('discount')
                                                <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-4 dimensions" style="display: {{ $type === 'variable' ? 'none' : 'block' }}">
                                            <div class="form-group">
                                                <label for="width">{{ __('Volume') }}</label>
                                                <input type="text" name="volume" id="volume" class="form-control number" value="{{ old('volume') }}" >
                                            </div>
                                        </div>
                                        @php
                                        $selected_tags = old('tags', []);
                                        @endphp
                                        <div class='col-sm-3'>
                                            <div class="form-group">
                                                <label for="tags">{{ __('Tags') }}</label>
                                                <select name="tags[]" id="tags" class="custom-select select2-multiple" multiple>
                                                    @foreach ($tags as $r)
                                                    <option value="{{ $r->id }}" @if( in_array($r->id, $selected_tags) ) selected @endif >{{ $r->tag_translates ? $r->tag_translates->title : $r->title }}</option>
                                                    @endforeach
                                                </select>
                                                @error('tags')
                                                <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                                @enderror
                                            </div>
                                        </div>




                                    </div>
                                     @foreach(getLocaleList() as $row)
                                        <h3>{{ __('Short Description ('.$row->title.')') }}</h3>
                                        <div class="form-group">
                                            <textarea name="short_description[{{$row->id}}]" id="short_description{{$row->id}}" cols="30" rows="5" class="form-control TinyEditor" >{!! old('short_description[{{$row->id}}]') !!}</textarea>
                                        </div>
                                        @endforeach
                                        <h3>{{ __('Additional Information') }}</h3>
                                        <div class="form-group">
                                            <textarea name="additional_information" id="additional_information" cols="30" rows="5" class="form-control TinyEditor">{!! old('additional_information') !!}</textarea>
                                        </div>
                                </div>
                            </div>

                            <!-- file upload -->
                            <x-admin.file-upload :name="'image'" />
                            @php
                            $selected_bundles = old('bundle_products', []);
                            @endphp
                            <div class="form-group" id="bundle_products_box" style="display: {{ $type === 'bundle' ? 'block' : 'none' }}">
                                <label for="bundle_products">{{ __('Bundle Products') }}</label>
                                <select name="bundle_products[]" id="bundle_products" class="custom-select select2-multiple" multiple>
                                    @foreach ($bundle_products as $r)
                                    <option value="{{ $r->id }}" @if( in_array($r->id, $selected_bundles) ) selected @endif>{{ $r->title }}</option>
                                    @endforeach
                                </select>
                                @error('bundle_products')
                                <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                @enderror
                            </div>
                            @php
                            $selected_attributes = old('attributes', []);
                            @endphp
                            <div id="variable_box" style="display: {{ $type === 'variable' ? 'block' : 'none' }}">
                                <h3>{{ __('Variations') }}</h3>
                                <div class="form-group">
                                    <label for="attributes">{{ __('Attributes') }}</label>
                                    <select name="attributes[]" id="attributes" class="custom-select select2-multiple" multiple>
                                        @foreach ($attributes as $r)
                                        <option value="{{ $r->id }}" @if( in_array($r->id, $selected_attributes) ) selected @endif >{{ $r->title }}</option>
                                        @endforeach
                                    </select>
                                    @error('attributes')
                                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                    @enderror
                                </div>
                                <div id="load_attributes"></div>
                                <div id="variations_box">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead id="variations_head">
                                            </thead>
                                            <tbody id="load_variations">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            @foreach(getLocaleList() as $val)
                            <h3>{{ __('Description ('.$val->title.')') }}</h3>
                            <div class="form-group">
                                <textarea name="data[{{$val->id}}]" id="data{{$val->id}}" cols="30" rows="5" class="form-control TinyEditor">{!! old('data[$val->id]') !!}</textarea>
                            </div>
                            @endforeach

                            <!-- gallery -->
                            <h3 class="mt-3">{{ __('Gallery') }}</h3>
                            <input type="hidden" name="gallery" id="gallery" value="">
                            <div id="dropzone" class="dropzone needsclick mb-3">
                                <div class="dz-message needsclick">
                                    <button type="button" class="dz-button">{{ __('Drop files here or click to upload.') }}</button>
                                </div>
                            </div>
<!--                            <div class="col-sm-4">-->
                                    <a class="btn btn-info" onclick="seotab()">SEO Options</a>
                            <!--</div>-->
                            <div class="col-sm-12" id="seotab"  style="display: none;" >
                                 <!-- seo fields -->
                                <x-seo-product-form-fields />
                            </div>

                            <!-- submit button -->
                            <div class="text-center">
                                <x-admin.publish-button />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="card">
                        <div class="card-header">
                            {{ __('Categories') }}<sup style="color:red;">*</sup>
                        </div>
                        <div class="card-body">
                            <div id="errorTxt"></div>
                            @php
                            $selected_categories = old('categories', []);
                            @endphp
                            <div id="categories_box" style="height: 400px; overflow: auto">
                                @foreach ($categories as $cat)
                                <div class="custom-control custom-checkbox mb-1">
                                    <input type="checkbox" class="custom-control-input" name="categories[]" id="cat_{{ $cat->id }}" value="{{ $cat->id }}" required @if ( in_array($cat->id, $selected_categories) ) checked @endif onchange="calculateMotFee()">
                                    <label class="custom-control-label" for="cat_{{ $cat->id }}">{{ $cat->title }}</label>
                                </div>
                                @if ( count($cat->subcategories) > 0 )
                                @include('admin.products.subcategoriesCheckbox', ['subcategories' => $cat->subcategories, 'selected_categories' => $selected_categories, 'level' => 1])

                                @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@php
$media_size = collect(config('media.sizes.product'))->first();
@endphp
@include('admin.includes.crop-modal', ['width' => $media_size['width'], 'height' => $media_size['height'], 'ratio' => $media_size['ratio']])
@endsection

@push('header')
<link rel="stylesheet" href="{{ asset('assets/backend') }}/css/dropzone.min.css" type="text/css" />
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet">
<!--<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />-->
<link rel="stylesheet" href="//select2.github.io/select2-bootstrap-theme/css/select2-bootstrap.css" rel="stylesheet">
<style>
    #advance {
        padding: 10px 10px;
        background-color: #f0f3f5;
        margin: 10px 0px;
    }
    #seotab {
        padding: 10px 10px;
        background-color: #f0f3f5;
        margin: 10px 0px;
    }
</style>
@endpush

@push('footer')
<x-validation />
<x-tinymce />
<script type="text/javascript" src="{{ asset('assets/backend') }}/js/tinymce.js"></script>
<script type="text/javascript" src="{{ asset('assets/backend') }}/js/dropzone.min.js"></script>
<!--<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>-->
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
<script>
function myFunction() {
    var x = document.getElementById("advance");
    if (x.style.display === "none") {
        x.style.display = "block";
    } else {
        x.style.display = "none";
    }
}
function seotab() {
    var x = document.getElementById("seotab");
    if (x.style.display === "none") {
        x.style.display = "block";
    } else {
        x.style.display = "none";
    }
}
</script>
<script type="text/javascript" charset="utf-8">
    const attributes = {!! json_encode($attributes) !!};
    var my_attributes = [];
    var selected_categories = [];
    $.fn.select2.defaults.set("theme", "bootstrap");
    // set validation rule for categories
    $.validator.setDefaults({
        messages: {
            'categories[]': {
            required: "{{ __('Choose at least one category') }}"
            }
        },
        errorPlacement: function(error, element) {
            if (element[0].type == 'checkbox') {
                error.appendTo('#errorTxt');
            } else {
                error.insertAfter(element);
            }
        }
    });
    $(document).ready(function(){
        $("#add_form").validate();
        $(".select2-multiple").select2({
            placeholder: '{{ __('Select options') }}',
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
    $(document).on('change', '#attributes', function () {
        let selected_attributes = $(this).val();
        // if no attribute selected
        if (selected_attributes.length == 0) {
            $('#variations_head').html('');
            $('#load_variations').html('');
            $('#load_attributes').html('');
            return false;
        }

        // append new attributes
        selected_attributes.forEach(function(r){

        // if new attribute
        if ($('#attribute_' + r).length == 0) {

        my_attributes.push(r);
        let get_attribute_data = attributes.filter(function(f){
        return f.id == r;
        })[0];
        let attribute_options = '';
        get_attribute_data.options.forEach(function(option){
        attribute_options += `<option value="${option.id}" data-title="${option.title}">${option.title}</option>`;
        });
        $('#load_attributes').append(`<div class="form-group" id="attribute_${get_attribute_data.id}">
              <label for="attribute_options_${get_attribute_data.id}">${get_attribute_data.title}</label>
              <select name="attribute_options_${get_attribute_data.id}[]" id="attribute_options_${get_attribute_data.id}" class="custom-select select2-multiple" multiple onchange="selectAttributeOptions()">
                ${attribute_options}
              </select>
            </div>`);
        loadSelect2();
        }
        });
        // get removed attributes
        let get_removed_attributes = my_attributes.filter(function(f){
        return !selected_attributes.includes(f);
        });
        // if attribute removed, update variations
        if (get_removed_attributes.length > 0) {
        get_removed_attributes.forEach(function(r){
        $('#attribute_' + r).remove();
        let findIndex = my_attributes.findIndex(function(f){
        return f == r;
        });
        my_attributes.splice(findIndex, 1);
        });
        selectAttributeOptions(); // generate variations again
        }
    });
    function selectAttributeOptions() {

    if (my_attributes.length == 0) {
    $('#load_variations').html('');
    $('#variations_head').html('');
    return false;
    }

    let options = [];
    my_attributes.forEach(function(r){

    let attribute_options = [];
    $(`#attribute_options_${r} option:selected`).each(function() {
    let option_id = $(this).val();
    let option_title = $(this).data('title');
    attribute_options.push({id: option_id, title: option_title});
    });
    if (attribute_options.length > 0) {
    options.push(attribute_options);
    }
    });
    if (options.length == 0) {
    $('#variations_head').html('');
    $('#load_variations').html('');
    return false;
    }

    let combinations = create_attributes_combinations(options);
    createVariations(combinations);
    }

    function createVariations(combinations) {

    let total_attributes = my_attributes.length;
    let attributes_head = '';
    let get_attributes_data = attributes.filter(function(r){
    return my_attributes.some(function(a){
    return a == r.id;
    });
    });
    get_attributes_data.forEach(function(r){
    let attribute_options = $('#attribute_options_' + r.id).val();
    if (attribute_options.length > 0) {
    attributes_head += `<th class="border-top-0 text-center">${r.title}</th>`;
    }
    });
    let variations_head_template = `<tr>
      ${attributes_head}
      <th class="border-top-0 text-center" width="10%">{{ __('SKU') }}</th>
      <th class="border-top-0 text-center" width="10%">{{ __('Price') }}</th>
      <th class="border-top-0 text-center" width="10%">{{ __('Discount') }}</th>
      <th class="border-top-0 text-center" width="10%">{{ __('Discount Type') }}</th>
      <th class="border-top-0 text-center" width="10%">{{ __('Stock') }}</th>
      <th class="border-top-0 text-center" width="10%">{{ __('Mot Fee') }}</th>
      <th class="border-top-0 text-center" width="10%">{{ __('Weight') }}</th>
      <th class="border-top-0 text-center" width="10%">{{ __('Length') }}</th>
      <th class="border-top-0 text-center" width="10%">{{ __('Height') }}</th>
      <th class="border-top-0 text-center" width="10%">{{ __('Width') }}</th>
      <th class="border-top-0 text-center" width="10%">{{ __('Volume') }}</th>
      <th class="border-top-0 text-center" width="10%">{{ __('Image') }}</th>
    </tr>`;
    $('#variations_head').html(variations_head_template);
    let variations = '';
    combinations.forEach(function(variation, index){
    variations += appendVariationRow(variation, index);
    });
    $('#load_variations').html(variations);
    }

    function appendVariationRow(variation, index) {

    let price = $('#price').val();
    let discount = $('#discount').val();
    let discount_type = $('#discount_type').val();
    let stock = $('#stock').val();
    // append attributes
    let variation_attributes = '';
    my_attributes.forEach(function(r){
    variation_attributes += `<input type="hidden" name="variations[${index}][attributes][]" value="${r}" />`;
    });
    // append options
    let variation_options = '';
    variation.forEach(function(v){
    variation_options += `<td class="text-center align-middle">${v.title}<input type="hidden" name="variations[${index}][options][]" value="${v.id}" /></td>`;
    });
    let template = `<tr>
      ${variation_options}
      <td class="text-center">${variation_attributes}
        <input type="text" name="variations[${index}][sku]" class="form-control" value="" required=required title="{{__('Please fill out this field')}}">
      </td>
      <td class="text-center">
        <input type="text" name="variations[${index}][price]" class="form-control variation-price number" value="${price}" required=required title="{{__('Please fill out this field')}}">
      </td>
      <td class="text-center">
          <input type="text" name="variations[${index}][discount]" class="form-control variation-price number" value="${discount}">
        </td>
        <td class="text-center">
            <select name="variations[${index}][discount_type]" id="discount_type" class="custom-select">
                <option value="">--{{ __('select') }}--</option>
                <option value="fixed">{{ __('Fixed') }}</option>
                <option value="percentage">{{ __('Percentage') }}</option>
            </select>
        </td>
      <td class="text-center">
        <input type="text" name="variations[${index}][stock]" class="form-control variation-stock number" value="${stock}" maxlength="4" required=required title="{{__('Please fill out this field')}}">
      </td>
      <td class="text-center">
        <input type="text" name="variations[${index}][mot-fee]" class="form-control variation-mot-fee number" value="">
      </td>
      <td class="text-center">
        <input type="text" name="variations[${index}][weight]" class="form-control variation-weight number" value="">
      </td>
      <td class="text-center">
        <input type="text" name="variations[${index}][length]" class="form-control variation-length number" value="">
      </td>
      <td class="text-center">
        <input type="text" name="variations[${index}][height]" class="form-control variation-height number" value="">
      </td>
      <td class="text-center">
        <input type="text" name="variations[${index}][width]" class="form-control variation-width number" value="">
      </td>
      <td class="text-center">
        <input type="text" name="variations[${index}][volume]" class="form-control variation-width number" value="">
      </td>
      <td class="text-center">
        <input type="file" name="variations[${index}][image]" accept="image/*">
      </td>
    </tr>`;
    return template;
    }

    function updateVariations(value, target) {
    var store_id = $('#store').val();
    if (store_id){
    if (target === 'price' || target === 'discount' || target === 'discount-type') {
    calculateMotFee();
    }
    }
    $('.variation-' + target).val(value);
    }

    function calculateMotFee() {
    let mot_commission = $('#mot_commission').val();
    let store_id = $('#store').val();
    let discounted_amount = getDiscountedAmount();
    if (!discounted_amount) {
    return;
    }

    let categories = getSelectedCategories();
    axios.post('{{ route('admin.api.get.mot.commission') }}', {
    store_id: store_id,
            categories: categories
    }).then(function(response) {

    $('#mot_commission').val(response.data.commission);
    mot_commission = response.data.commission;
    if (!mot_commission) {
    return;
    }

    let mot_fee_amount = discounted_amount.toFixed(2) * (mot_commission / 100);
    $('#mot_fee').val(mot_fee_amount.toFixed(2));
    });
    }

    function getSelectedCategories() {
    selected_categories = [];
    $('input[name="categories[]"]:checked').each(function(){
    let id = $(this).val();
    selected_categories.push(id);
    });
    return selected_categories;
    }

    function getDiscountedAmount() {
    let price = $('#price').val();
    let discount = $('#discount').val();
    let discount_type = $('#discount_type').val();
    price = Number.parseFloat(price);
    discount = Number.parseFloat(discount);
    if (!price) {
    return;
    }

    if (!discount) {
    return price;
    }

    if (discount_type === 'percentage') {
    return price * (1 - discount / 100);
    }

    if (discount > 0 && discount < price && discount_type === 'fixed') {
    return price - discount;
    }

    return price;
    }

    function create_attributes_combinations(data) {

    let combo = [[]];
    data.forEach(function(options){
    let temp = [];
    combo.forEach(function(item){
    options.forEach(function(option){
    let merge = item.concat([{id: option.id, title: option.title}]);
    temp.push(merge);
    });
    });
    combo = temp;
    });
    return combo;
    }

    function loadSelect2() {
    $(".select2-multiple").select2({
    placeholder: 'Select options',
            width: '100%'
    });
    }

    function selectProductType(type) {
    if (type === 'variable') {
    $('#brand_box').show();
    $('#brand_box_main').show();
    $('#stock').prop('disabled', true);
    $('#variable_box').show();
    $('#discount_box, #discount_type_box, #mot_fee_box').hide();
    $('.dimensions').hide();
    return;
    }
    $('#discount_box, #discount_type_box, #mot_fee_box').show();
    if (type === 'bundle') {
    $('#brand_box_main').hide();
    $('#bundle_products_box').show();
    return;
    }

    $('#brand_box').show();
    $('#brand_box_main').show();
    $('#price').prop('disabled', false);
    $('#stock').prop('disabled', false);
    $('#variable_box').hide();
    $('#bundle_products_box').hide();
    $('.dimensions').show();
    }

    function freeDelivery(type) {
    if (type == 1) {
    $('#delivery_box').hide();
    return;
    }

    $('#delivery_box').show();
    }

    function additionalBrand(type) {
    if (type == 1) {
    $('#additional_brand').hide();
    $('#brand_box').show();
    return;
    }
    $('#additional_brand').show();
    $('#brand_box').hide();
    }

    function freeDeliveryVariation(type, target_box) {
    if (type == 1) {
    $('#' + target_box).hide();
    return;
    }

    $('#' + target_box).show();
    }

    // dropzone
    var fileList = [];
    Dropzone.autoDiscover = false;
    var myDropzone = new Dropzone("#dropzone", {
    url: "{{ route('seller.products.gallery.upload') }}",
            method: "post",
            addRemoveLinks: true,
            parallelUploads: 13,
            uploadMultiple: true,
            maxFiles: 13,
            maxFilesize: 2, // MB,
            acceptedFiles: ".jpeg,.jpg,.png",
            headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            renameFile: function (file) {
            let newName = new Date().getTime() + '_' + file.name.replace("=", "").replace(/\s+/g, "_").replace(/[^a-z0-9\_\-\.]/i, "");
            return newName;
            }
    });
    myDropzone.on("sending", function(file, xhr, formData) {
    $('button#SaveBtn').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> {{ __('Processing...') }}').prop('disabled', true);
    });
    myDropzone.on("complete", function(file) {

    if (file.status != 'error') {
    fileList.push(file.upload.filename);
    $('#gallery').val(fileList.toString());
    }

    $('button#SaveBtn').html($('button#SaveBtn').data('original')).prop('disabled', false);
    });
    myDropzone.on("removedfile", function(file) {

    let token = '{{ csrf_token() }}';
    let filename = '';
    if (typeof (file.upload) != "undefined" && file.upload !== null) {
    filename = file.upload.filename;
    fileList.splice($.inArray(file.upload.filename, fileList), 1);
    } else {
    filename = file.name;
    fileList.splice($.inArray(file.name, fileList), 1);
    }

    $('#gallery').val(fileList.toString());
    $.ajax({
    url: "{{ route('seller.products.gallery.delete') }}",
            type: "POST",
            data: "filename=" + filename + "&_token=" + token,
            cache: false,
            dataType:  'json',
            success : function (response) {}
    });
    });
    function InvalidMsg(textbox) {

    if (textbox.value == '') {
    textbox.setCustomValidity('{{__('Please fill out this field')}}');
    }
    else {
    textbox.setCustomValidity('');
    }
    return true;
    }

    $(document).ready(function () {
        $('#store_sku').keyup(function(){
            let storeSKU = $('#store_sku').val();
            let storeId = $('#store').val();
            let storeName = $( "#storeName" ).val();
            generateSku(storeId, storeSKU, storeName);
        });
    });
    function generateSku(storeId, storeSku, storeName) {

            var storecode = makeSkuCode(storeName);
            $('#sku').val(storecode + storeId + '-' + storeSku);
        }

        function makeSkuCode(storeName) {
            var storeCode ='';
            var  storeNamearr = storeName.replace(/\s{2,}/g, ' ').split(" ");

            if (storeNamearr.length == 1) {
                storeCode = storeNamearr[0].substr(0, 3);
            }
            if (storeNamearr.length == 2) {
                storeCode = storeNamearr[0].substr(0, 1) + storeNamearr[1].substr(0, 1) + storeNamearr[1].substr(-1);
            }
            if (storeNamearr.length > 2) {
                storeCode = storeNamearr[0].substr(0, 1) + storeNamearr[1].substr(0, 1) + storeNamearr[2].substr(0, 1);
            }

            return storeCode;
        }
</script>
@endpush
