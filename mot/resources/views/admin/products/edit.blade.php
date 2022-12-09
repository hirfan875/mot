@extends('admin.layouts.app')

@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.products') }}">{{ $section_title }}</a></li>
        <li class="breadcrumb-item active">{{ $title }}</li>
    </ol>
    <div class="container-fluid">
        <div class="animated fadeIn">
            <form action="{{ route('admin.products.edit', ['product' => $row->id]) }}" method="POST"
                  enctype="multipart/form-data" id="edit_form">
                @csrf
                <input type="hidden" name="mot_commission" id="mot_commission" value="{{ $mot_commission }}">
                <div class="row">
                    <div class="col-lg-9">
                        <div class="card">
                            <div class="card-header">
                                {{ $title }}
                                <x-admin.back-button :url="route('admin.products')"/>
                            </div>
                                @foreach ($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            <div class="card-body">
                                <!-- alerts -->
                                <x-alert class="alert-success" :status="session('success')"/>
                                <x-alert class="alert-danger" :status="session('error')"/>
                                <div class="row">
                                    @foreach(getLocaleList() as $val)
                                        @php
                                            $ctitle= $row->title;
                                            if($row->product_translate){
                                                foreach($row->product_translate as $r){
                                                    if($val->code == $r->language_code){
                                                        $ctitle = $r->title;
                                                    }
                                                }
                                            }
                                        @endphp
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="title">{{ __('Title ('.$val->title.')') }}</label>
                                                <input type="text" name="title[{{$val->id}}]" id="title_{{$val->id}}"
                                                       class="form-control" value="{{ old('title[]', $ctitle) }}"
                                                       {{$val->is_default == 'Yes' ? 'required' : ''}} title="{{__('Please fill out this field')}}"
                                                       onfocusout="metaTitle({{$row->id}})"/>
                                                @error('title[]')
                                                <span class="invalid-feedback d-block"
                                                      role="alert"> <strong>{{ __($message) }}</strong> </span>
                                                @enderror
                                            </div>
                                        </div>
                                    @endforeach
                                    <div class="col-sm-9">
                                        <div class="form-group">
                                            <label for="slug">{{ __('slug') }}</label>
                                            <input type="text" name="slug" id="slug" class="form-control"
                                                   value="{{ old('slug', $row->slug) }}">
                                            @error('slug')
                                            <span class="invalid-feedback d-block"
                                                  role="alert"> <strong>{{ __($message) }}</strong> </span>
                                            @enderror
                                        </div>
                                    </div>
                                    @php
                                        $type = old('type', $row->type);
                                    @endphp
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="type">{{ __('Product Type') }}</label>
                                            <select name="type" id="type" class="custom-select"
                                                    onchange="selectProductType(this.value)">
                                                <option value="simple"
                                                        @if ( $type === 'simple' ) selected @endif >{{ __('Simple') }}</option>
                                                <option value="variable"
                                                        @if ( $type === 'variable' ) selected @endif >{{ __('Variable') }}</option>
                                                <option value="bundle"
                                                        @if ( $type === 'bundle' ) selected @endif >{{ __('Bundle') }}</option>
                                            </select>
                                            @error('type')
                                            <span class="invalid-feedback d-block"
                                                  role="alert"> <strong>{{ __($message) }}</strong> </span>
                                            @enderror
                                        </div>
                                    </div>
                                    @php
                                        $brand = old('brand', $row->brand_id);
                                    @endphp
                                    <div class="col-sm-4" id="brand_box"
                                         style="display: {{ $type === 'bundle' ? 'none' : 'block' }}">
                                        <div class="form-group">
                                            <label for="brand">{{ __('Brand') }}</label>
                                            <select name="brand" id="brand" class="custom-select">
                                                <option value="">--{{ __('select') }}--</option>
                                                @foreach ($brands as $r)
                                                    <option value="{{ $r->id }}"
                                                            @if ( $brand == $r->id ) selected @endif >{{ $r->brand_translates ? $r->brand_translates->title : $r->title}}</option>
                                                @endforeach
                                            </select>
                                            @error('brand')
                                            <span class="invalid-feedback d-block"
                                                  role="alert"> <strong>{{ __($message) }}</strong> </span>
                                            @enderror
                                        </div>
                                    </div>
                                    @php
                                        $store = old('store', $row->store_id);
                                    @endphp
                                    <div class="col-sm-4" id="store_box">
                                        <div class="form-group">
                                            <label for="store">{{ __('Store') }}</label>
                                            <select name="store" id="store" class="custom-select"
                                                    onchange="calculateMotFee()">
                                                <option value="">--{{ __('select') }}--</option>
                                                @foreach ($stores as $r)
                                                    <option value="{{ $r->id }}"
                                                            @if ( $store == $r->id ) selected @endif >{{isset($r->store_profile_translates)? $r->store_profile_translates->name : $r->name}}</option>
                                                @endforeach
                                            </select>
                                            @error('store')
                                            <span class="invalid-feedback d-block"
                                                  role="alert"> <strong>{{ __($message) }}</strong> </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="sku">{{ __('SKU') }}</label>
                                            <input type="text" name="store_sku" id="store_sku" class="form-control"    value="{{ old('store_sku', $row->store_sku) }}">
                                            <input type="hidden" name="sku" id="sku" class="form-control"  value="{{ old('sku', $row->sku) }}">
                                            @error('sku')
                                            <span class="invalid-feedback d-block"
                                                  role="alert"> <strong>{{ __($message) }}</strong> </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="price">{{ __('Price') }}</label>
                                            <input type="text" name="price" id="price" class="form-control number"
                                                   value="{{ old('price', $row->price) }}"
                                                   onfocusout="updateVariations(this.value, 'price')">
                                            @error('price')
                                            <span class="invalid-feedback d-block"
                                                  role="alert"> <strong>{{ __($message) }}</strong> </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4" id="discount_box"
                                         style="display: {{ $type === 'variable' ? 'none' : 'block' }}">
                                        <div class="form-group">
                                            <label for="discount">{{ __('Discount') }}</label>
                                            <input type="text" name="discount" id="discount" class="form-control number"
                                                   value="{{ old('discount', $row->discount) }}"
                                                   onfocusout="updateVariations(this.value, 'discount')">
                                            @error('discount')
                                            <span class="invalid-feedback d-block"
                                                  role="alert"> <strong>{{ __($message) }}</strong> </span>
                                            @enderror
                                        </div>
                                    </div>
                                    @php
                                        $discount_type = old('discount_type', $row->discount_type);
                                    @endphp
                                    <div class="col-sm-4" id="discount_type_box"
                                         style="display: {{ $type === 'variable' ? 'none' : 'block' }}">
                                        <div class="form-group">
                                            <label for="discount_type">{{ __('Discount Type') }}</label>
                                            <select name="discount_type" id="discount_type" class="custom-select"
                                                    onchange="updateVariations(this.value, 'discount-type')">
                                                <option value="">--{{ __('select') }}--</option>
                                                <option value="fixed"
                                                        @if ( $discount_type === 'fixed' ) selected @endif >{{ __('Fixed') }}</option>
                                                <option value="percentage"
                                                        @if ( $discount_type === 'percentage' ) selected @endif >{{ __('Percentage') }}</option>
                                            </select>
                                            @error('discount_type')
                                            <span class="invalid-feedback d-block"
                                                  role="alert"> <strong>{{ __($message) }}</strong> </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4" id="mot_fee_box">
                                        <div class="form-group">
                                            <label for="mot_fee">{{ __('Mot Fee') }}</label>
                                            <input type="text" name="mot_fee" id="mot_fee" class="form-control number"
                                                   value="{{ $mot_commission_amount }}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="stock">{{ __('Stock') }}</label>
                                            <input type="text" name="stock" id="stock" class="form-control number"
                                                   value="{{ old('stock', $row->stock) }}" maxlength="4"
                                                   onfocusout="updateVariations(this.value, 'stock')"
                                                   @if ($type === 'variable') disabled @endif>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <p>{{__('Note: weight & volumetric weight required for shipment purposes.')}}</p>
                                    </div>

                                    <div class="col-sm-4 dimensions"
                                         style="display: {{ $type === 'variable' ? 'none' : 'block' }}">
                                        <div class="form-group">
                                            <label for="weight">{{ __('Weight') }}</label>
                                            <input type="text" name="weight" id="weight" class="form-control number"
                                                   value="{{ old('weight', $row->weight) }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-3 dimensions"
                                         style="display: {{ $type === 'variable' ? 'none' : 'block' }}">
                                        <div class="form-group">
                                            <label for="length">{{ __('Length') }}</label>
                                            <input type="text" name="length" id="length" class="form-control number"
                                                   value="{{ old('length', $row->length) }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-3 dimensions"
                                         style="display: {{ $type === 'variable' ? 'none' : 'block' }}">
                                        <div class="form-group">
                                            <label for="height">{{ __('Height') }}</label>
                                            <input type="text" name="height" id="height" class="form-control number"
                                                   value="{{ old('height', $row->height) }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-3 dimensions"
                                         style="display: {{ $type === 'variable' ? 'none' : 'block' }}">
                                        <div class="form-group">
                                            <label for="width">{{ __('Width') }}</label>
                                            <input type="text" name="width" id="width" class="form-control number"
                                                   value="{{ old('width', $row->width) }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-3 dimensions"
                                         style="display: {{ $type === 'variable' ? 'none' : 'block' }}">
                                        <div class="form-group">
                                            <label for="volume">{{ __('Volume') }}</label>
                                            <input type="text" name="volume" id="volume" class="form-control number"
                                                   value="{{ old('volume', $row->volume) }}">
                                        </div>
                                    </div>
                                </div>
                                @php
                                    $selected_tags = old('tags', $product_tags_ids);
                                @endphp
                                <div class="form-group">
                                    <label for="tags">{{ __('Tags') }}</label>
                                    <select name="tags[]" id="tags" class="custom-select select2-multiple" multiple>
                                        @foreach ($tags as $r)
                                            <option value="{{ $r->id }}"
                                                    @if( in_array($r->id, $selected_tags) ) selected @endif >{{ $r->title }}</option>
                                        @endforeach
                                    </select>
                                    @error('tags')
                                    <span class="invalid-feedback d-block"
                                          role="alert"> <strong>{{ __($message) }}</strong> </span>
                                    @enderror
                                </div>
                                <!-- file upload -->
                                <x-admin.file-upload :name="'image'" :file="$row->image"
                                                     :thumbnail="$row->getMedia('image', 'thumbnail')"
                                                     :croproute="'admin'" :croptype="'product_main'"
                                                     :imageid="$row->id"/>
                                @php
                                    $selected_bundles = old('bundle_products', $product_bundle_ids);
                                @endphp
                                <div class="form-group" id="bundle_products_box"
                                     style="display: {{ $type === 'bundle' ? 'block' : 'none' }}">
                                    <label for="bundle_products">{{ __('Bundle Products') }}</label>
                                    <select name="bundle_products[]" id="bundle_products"
                                            class="select2-custom-select select2-multiple" multiple>
                                        @foreach ($bundle_products as $r)
                                            <option value="{{ $r->id }}"
                                                    @if( in_array($r->id, $selected_bundles) ) selected @endif>{{ $r->title }}</option>
                                        @endforeach
                                    </select>
                                    @error('bundle_products')
                                    <span class="invalid-feedback d-block"
                                          role="alert"> <strong>{{ __($message) }}</strong> </span>
                                    @enderror
                                </div>
                                @php
                                    $selected_attributes = old('attributes', $product_attributes_ids);
                                @endphp
                                <div id="variable_box" style="display: {{ $type === 'variable' ? 'block' : 'none' }}">
                                    <h3>{{ __('Variations') }}</h3>
                                    <div class="form-group">
                                        <label for="attributes">{{ __('Attributes') }}</label>
                                        <select name="attributes[]" id="attributes"
                                                class="custom-select select2-multiple" multiple>
                                            @foreach ($attributes as $r)
                                                <option value="{{ $r->id }}"
                                                        @if( in_array($r->id, $selected_attributes) ) selected @endif >{{ $r->title }}</option>
                                            @endforeach
                                        </select>
                                        @error('attributes')
                                        <span class="invalid-feedback d-block"
                                              role="alert"> <strong>{{ __($message) }}</strong> </span>
                                        @enderror
                                    </div>
                                    <div id="load_attributes">
                                        @foreach ($product_attributes as $r)
                                            <div class="form-group" id="attribute_{{ $r->id }}">
                                                <label for="attribute_options_{{ $r->id }}">{{ $r->title }}</label>
                                                <select name="attribute_options_{{ $r->id }}[]"
                                                        id="attribute_options_{{ $r->id }}"
                                                        class="custom-select select2-multiple" multiple
                                                        onchange="selectAttributeOptions()">
                                                    @foreach ($r->options as $option)
                                                        <option value="{{ $option->id }}"
                                                                data-title="{{ $option->title }}"
                                                                @if( in_array($option->id, $product_attributes_optoins_ids) ) selected @endif >{{ $option->title }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div id="variations_box">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead id="variations_head">
                                                @if ( $type === 'variable' && $row->variations->count() > 0 )
                                                    <tr>
                                                        @foreach ($product_attributes as $r)
                                                            <th class="border-top-0 text-center">{{ $r->title }}</th>
                                                        @endforeach
                                                        <th class="border-top-0 text-center"
                                                            width="10%">{{ __('SKU') }}</th>
                                                        <th class="border-top-0 text-center"
                                                            width="10%">{{ __('Price') }}</th>
                                                            <th class="border-top-0 text-center"
                                                                width="10%">{{ __('Discount') }}</th>
                                                            <th class="border-top-0 text-center"
                                                                width="10%">{{ __('Discount Type') }}</th>
                                                        <th class="border-top-0 text-center"
                                                            width="10%">{{ __('Stock') }}</th>
                                                        <th class="border-top-0 text-center"
                                                            width="10%">{{ __('Mot Fee') }}</th>
                                                        <th class="border-top-0 text-center"
                                                            width="10%">{{ __('Weight') }}</th>
                                                        <th class="border-top-0 text-center"
                                                            width="10%">{{ __('Length') }}</th>
                                                        <th class="border-top-0 text-center"
                                                            width="10%">{{ __('Height') }}</th>
                                                        <th class="border-top-0 text-center"
                                                            width="10%">{{ __('Width') }}</th>
                                                        <th class="border-top-0 text-center"
                                                            width="10%">{{ __('Volume') }}</th>
                                                        <th class="border-top-0 text-center"
                                                            width="10%">{{ __('Image') }}</th>
                                                    </tr>
                                                @endif
                                                </thead>
                                                <tbody id="load_variations">
                                                @foreach ($row->variations as $key=>$variation)
                                                    <tr>
                                                        @foreach ($variation->variation_attributes as $r)
                                                            <td class="text-center align-middle">
                                                                {{ $r->option->title }}
                                                                <input type="hidden"
                                                                       name="variations[{{ $key }}][options][]"
                                                                       value="{{ $r->option_id }}"/>
                                                                <input type="hidden"
                                                                       name="variations[{{ $key }}][attributes][]"
                                                                       value="{{ $r->attribute_id }}"/>
                                                            </td>
                                                        @endforeach
                                                        <td class="text-center">
                                                            <input type="hidden" name="variations[{{ $key }}][id]"
                                                                   value="{{ $variation->id }}"/>
                                                            <input type="text" name="variations[{{ $key }}][sku]"
                                                                   class="form-control"
                                                                   value="{{ $variation->store_sku }}" required
                                                                   title="{{__('Please fill out this field')}}">
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" name="variations[{{ $key }}][price]"
                                                                   class="form-control variation-price number"
                                                                   value="{{ $variation->price }}" required
                                                                   title="{{__('Please fill out this field')}}">
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" name="variations[{{ $key }}][discount]"
                                                                   class="form-control variation-price number"
                                                                   value="{{ $variation->discount }}"
                                                                   title="{{__('Please fill out this field')}}">
                                                        </td>
                                                        <td class="text-center">
                                                            <select name="variations[{{ $key }}][discount_type]" id="discount_type" class="custom-select">
                                                                <option value="">--{{ __('select') }}--</option>
                                                                <option value="fixed"
                                                                        @if ( $variation->discount_type === 'fixed' ) selected @endif >{{ __('Fixed') }}</option>
                                                                <option value="percentage"
                                                                        @if ( $variation->discount_type === 'percentage' ) selected @endif >{{ __('Percentage') }}</option>
                                                            </select>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" name="variations[{{ $key }}][stock]"
                                                                   class="form-control variation-stock number"
                                                                   value="{{ $variation->stock }}" maxlength="4"
                                                                   required
                                                                   title="{{__('Please fill out this field')}}">
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" name="variations[{{ $key }}][mot_fee]"
                                                                   class="form-control variation-mot_fee number"
                                                                   value="{{ $variation->mot_fee }}">
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" name="variations[{{ $key }}][weight]"
                                                                   class="form-control variation-weight number"
                                                                   value="{{ $variation->weight }}">
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" name="variations[{{ $key }}][length]"
                                                                   class="form-control variation-length number"
                                                                   value="{{ $variation->length }}">
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" name="variations[{{ $key }}][height]"
                                                                   class="form-control variation-height number"
                                                                   value="{{ $variation->height }}">
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" name="variations[{{ $key }}][width]"
                                                                   class="form-control variation-width number"
                                                                   value="{{ $variation->width }}">
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" name="variations[{{ $key }}][volume]"
                                                                   class="form-control variation-volume number"
                                                                   value="{{ $variation->volume }}">
                                                        </td>
                                                        <td class="text-center">
                                                            @php
                                                                $field_name = "variations[$key][image]";
                                                            @endphp
                                                            <x-admin.file-upload :name="$field_name"
                                                                                 :file="$variation->image"
                                                                                 :thumbnail="$variation->getMedia('image', 'thumbnail')"
                                                                                 :imageid="$variation->id"/>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                @foreach(getLocaleList() as $val)
                                    @php
                                        $cShortDescription = $row->short_description;
                                        if($row->product_translate){
                                            foreach($row->product_translate as $r){
                                                if($val->code == $r->language_code){
                                                    $cShortDescription = $r->short_description;
                                                }
                                            }
                                        }
                                    @endphp
                                    <h3>{{ __('Short Description ('.$val->title.')') }}</h3>
                                    <div class="form-group">
                                        <textarea name="short_description[{{$val->id}}]"
                                                  id="short_description_{{$val->id}}" cols="30" rows="5"
                                                  class="form-control TinyEditor">{!! old('short_description[{{$val->id}}]', $cShortDescription) !!}</textarea>
                                    </div>
                                @endforeach
                                @foreach(getLocaleList() as $val)
                                    @php
                                        $cdata= $row->data;
                                        if($row->product_translate){
                                            foreach($row->product_translate as $r){
                                                if($val->code == $r->language_code){
                                                    $cdata = $r->data;
                                                }
                                            }
                                        }
                                    @endphp
                                    <h3>{{ __('Description ('.$val->title.')') }}</h3>
                                    <div class="form-group">
                                        <textarea onblur="metaDesc({{$row->id}})" name="data[{{$val->id}}]"
                                                  id="data_{{$val->id}}" cols="30" rows="5"
                                                  class="form-control TinyEditor">{!! old('data[{{$val->id}}]', $cdata) !!}</textarea>
                                    </div>
                                @endforeach

                                <h3>{{ __('Additional Information') }}</h3>
                                <div class="form-group">
                                    <textarea name="additional_information" id="additional_information" cols="30"
                                              rows="5"
                                              class="form-control TinyEditor">{!! old('additional_information', $row->additional_information) !!}</textarea>
                                </div>
                                <!-- gallery -->
                                <h3 class="mt-3">{{ __('Gallery') }}</h3>
                                <input type="hidden" name="gallery" id="gallery" value="">
                                <div id="dropzone" class="dropzone needsclick mb-3">
                                    <div class="dz-message needsclick">
                                        <button type="button"
                                                class="dz-button">{{ __('Drop files here or click to upload.') }}</button>
                                    </div>
                                </div>
                                <div id="template-preview" style="display: none">
                                    <div class="dz-preview dz-file-preview">
                                        <div class="dz-image"><img data-dz-thumbnail/></div>
                                        <div class="dz-details">
                                            <div class="dz-size" data-dz-size></div>
                                            <div class="dz-filename"><span data-dz-name></span></div>
                                        </div>
                                        <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span>
                                        </div>
                                        <div class="dz-error-message"><span data-dz-errormessage></span></div>
                                        <a class="dz-remove dz-crop-link" href="javascript:;"
                                           target="_blank">{{ __('Crop file') }}</a>
                                    </div>
                                </div>
                                <!-- seo fields -->
                                <x-seo-product-form-fields :row="$row"/>
                                <!-- submit button -->
                                <div class="text-center">
                                    <x-admin.save-changes-button/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="card">
                            <div class="card-header">
                                {{ __('Categories') }}
                            </div>
                            <div class="card-body">
                                <div id="errorTxt">
                                    @error('categories') <label class="error"
                                                                style="display: block;">{{ __($message) }}</label> @enderror
                                </div>
                                @php
                                    $selected_categories = old('categories', $product_categories_ids);
                                @endphp
                                <div id="categories_box" style="height: 400px; overflow: auto">
                                    @foreach ($categories as $cat)
                                        <div class="custom-control custom-checkbox mb-1">
                                            <input type="checkbox" class="custom-control-input" name="categories[]"
                                                   id="cat_{{ $cat->id }}" value="{{ $cat->id }}"
                                                   @if ( in_array($cat->id, $selected_categories) ) checked
                                                   @endif onchange="calculateMotFee()">
                                            <label class="custom-control-label"
                                                   for="cat_{{ $cat->id }}">{{$cat->category_translates ? $cat->category_translates->title : $cat->title}}</label>
                                        </div>
                                        @if ( count($cat->subcategories) > 0 )
                                            @include('admin.products.subcategoriesCheckbox', ['subcategories' => $cat->subcategories, 'selected_categories' => $selected_categories, 'level' => 1])
                                            @foreach ($cat->subcategories as $sub)
                                            <!--                  <div class="custom-control custom-checkbox ml-4">
                    <input type="checkbox" class="custom-control-input" name="categories[]" id="cat_{{ $sub->id }}" value="{{ $sub->id }}"  @if ( in_array($sub->id, $selected_categories) ) checked @endif onchange="calculateMotFee()">
                    <label class="custom-control-label" for="cat_{{ $sub->id }}">{{$sub->category_translates ? $sub->category_translates->title : $sub->title}}</label>
                  </div>-->
                                            @endforeach
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
    <link rel="stylesheet" href="{{ asset('assets/backend') }}/css/dropzone.min.css" type="text/css"/>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//select2.github.io/select2-bootstrap-theme/css/select2-bootstrap.css"
          rel="stylesheet">
@endpush

@push('footer')
    <x-validation/>
    <x-tinymce/>
    <script type="text/javascript"
            src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script type="text/javascript" src="{{ asset('assets/backend') }}/js/tinymce.js"></script>
    <script type="text/javascript" src="{{ asset('assets/backend') }}/js/dropzone.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
    <script type="text/javascript" charset="utf-8">
        $("#store").on("change", function () {
            var store_id = $(this).val();
            let storeSKU = $('#store_sku').val();
            let storeName = $("#store option:selected").text();

            generateSku(store_id, storeSKU, storeName);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                }
            });
            $.ajax({
                type: "POST",
                url: `{{ route('admin.get-products') }}`,
                data: {store_id: store_id}
            }).done(function (data) {
                var options = '';
                for (var i = 0; i < data.products.length; i++) { // Loop through the data & construct the options
                    options += '<option value="' + data.products[i].id + '">' + data.products[i].title + '</option>';
                }
                // Append to the html
                $('#bundle_products').html(options);
            });
        });
    </script>
    <script type="text/javascript" charset="utf-8">
        const attributes = {!! json_encode($attributes) !!};
        const existing_variations = {!! json_encode($row->variations) !!};
        var my_attributes = {!! json_encode($product_attributes_ids) !!};
        $.fn.select2.defaults.set("theme", "bootstrap");

        // set validation rule for categories
        $.validator.setDefaults({
            messages: {
                'categories[]': {
                    required: "{{ __('Choose at least one category') }}"
                }
            },
            errorPlacement: function (error, element) {
                if (element[0].type == 'checkbox') {
                    error.appendTo('#errorTxt');
                } else {
                    error.insertAfter(element);
                }
            }
        });

        $(document).ready(function () {
            $("#edit_form").validate();
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
            console.log('appends attributes');
            let selected_attributes = $(this).val();

            // if no attribute selected
            if (selected_attributes.length == 0) {
                $('#variations_head').html('');
                $('#load_variations').html('');
                $('#load_attributes').html('');
                return false;
            }

            // append new attributes
            selected_attributes.forEach(function (r) {

                // if new attribute
                if ($('#attribute_' + r).length == 0) {

                    my_attributes.push(r);
                    let get_attribute_data = attributes.filter(function (f) {
                        return f.id == r;
                    })[0];

                    let attribute_options = '';
                    get_attribute_data.options.forEach(function (option) {
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
            let get_removed_attributes = my_attributes.filter(function (f) {
                return !selected_attributes.includes(f);
            });

            // if attribute removed, update variations
            if (get_removed_attributes.length > 0) {
                get_removed_attributes.forEach(function (r) {
                    $('#attribute_' + r).remove();
                    let findIndex = my_attributes.findIndex(function (f) {
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
            my_attributes.forEach(function (r) {

                let attribute_options = [];
                $(`#attribute_options_${r} option:selected`).each(function () {
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
            let get_attributes_data = attributes.filter(function (r) {
                return my_attributes.some(function (a) {
                    return a == r.id;
                });
            });
            get_attributes_data.forEach(function (r) {
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
        <th class="border-top-0 text-center" width="5%">{{ __('Stock') }}</th>
        <th class="border-top-0 text-center" width="5%">{{ __('Mot Fee') }}</th>
        <th class="border-top-0 text-center" width="10%">{{ __('Weight') }}</th>
        <th class="border-top-0 text-center" width="10%">{{ __('Length') }}</th>
        <th class="border-top-0 text-center" width="10%">{{ __('Height') }}</th>
        <th class="border-top-0 text-center" width="10%">{{ __('Width') }}</th>
        <th class="border-top-0 text-center" width="10%">{{ __('Volume') }}</th>
        <th class="border-top-0 text-center" width="10%">{{ __('Image') }}</th>
      </tr>`;

            $('#variations_head').html(variations_head_template);

            let variations = '';
            combinations.forEach(function (variation, index) {
                console.log(variation, index);
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
            my_attributes.forEach(function (r) {
                variation_attributes += `<input type="hidden" name="variations[${index}][attributes][]" value="${r}" />`;
            });

            // append options
            let variation_options = '';
            variation.forEach(function (v) {
                variation_options += `<td class="text-center align-middle">${v.title}<input type="hidden" name="variations[${index}][options][]" value="${v.id}" /></td>`;
            });

            // check in existing variations
            let check_existing_variant = checkExistingVariation(variation);
            let variation_id = '';
            if (check_existing_variant.length > 0) {
                variation_id = `<input type="hidden" name="variations[${index}][id]" value="${check_existing_variant[0].id}" />`;
            }

            let template = `<tr>
        ${variation_options}
        <td class="text-center">
          <input type="text" name="variations[${index}][sku]" class="form-control" value="" required=required title="{{__('Please fill out this field')}}">
          ${variation_attributes}
          ${variation_id}
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

        function checkExistingVariation(combo) {

            return existing_variations.filter(function (row) {
                return row.variation_attributes.every(function (attribute) {
                    return combo.some(function (r) {
                        return r.id == attribute.option_id;
                    });
                });
            });
        }

        function updateVariations(value, target) {
            var store_id = $('#store').val();
            if (store_id) {
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
            console.log(mot_commission);
            console.log(store_id);
            console.log(discounted_amount);
            if (!discounted_amount) {
                return;
            }

            let categories = getSelectedCategories();

            axios.post('{{ route('admin.api.get.mot.commission') }}', {
                store_id: store_id,
                categories: categories
            }).then(function (response) {

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
            $('input[name="categories[]"]:checked').each(function () {
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
            data.forEach(function (options) {
                let temp = [];
                combo.forEach(function (item) {
                    options.forEach(function (option) {
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
                $('#brand_box, #store_box').show();
                $('#stock').prop('disabled', true);
                $('#variable_box').show();
                $('#discount_box, #discount_type_box').hide();
                $('.dimensions').hide();

                return;
            }

            $('#price').prop('disabled', false);
            $('#stock').prop('disabled', false);
            $('#discount_box, #discount_type_box').show();
            $('.dimensions').show();
            if (type === 'bundle') {
                $('#brand_box').hide();
                $('#bundle_products_box').show();
                return;
            }

            $('#brand_box, #store_box').show();
            $('#variable_box').hide();
            $('#bundle_products_box').hide();
        }

        function freeDelivery(type) {
            if (type == 1) {
                $('#delivery_box').hide();
                return;
            }

            $('#delivery_box').show();
        }

        function freeDeliveryVariation(type, target_box) {
            if (type == 1) {
                $('#' + target_box).hide();
                return;
            }

            $('#' + target_box).show();
        }

        // dropzone
        var gallery = {!! json_encode($row->gallery) !!};
        var product_id = {{ $row->id }};
        var fileList = [];
        Dropzone.autoDiscover = false;
        var myDropzone = new Dropzone("#dropzone", {
            url: "{{ route('admin.products.gallery.upload') }}",
            method: "post",
            addRemoveLinks: true,
            parallelUploads: 13,
            uploadMultiple: true,
            maxFiles: 13,
            maxFilesize: 2, // MB,
            acceptedFiles: ".jpeg,.jpg,.png",
            previewTemplate: document.querySelector('#template-preview').innerHTML,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            renameFile: function (file) {
                let newName = new Date().getTime() + '_' + file.name.replace("=", "").replace(/\s+/g, "_").replace(/[^a-z0-9\_\-\.]/i, "");
                return newName;
            },
            init: function () {
                var thisDropzone = this;

                gallery.map(function (r, index) {

                    fileList.push(r.image);
                    var mockFile = {name: r.image};
                    let image_url = "{{ asset('storage') }}/original/" + r.image;

                    thisDropzone.files.push(mockFile);
                    thisDropzone.options.addedfile.call(thisDropzone, mockFile);
                    thisDropzone.options.thumbnail.call(thisDropzone, mockFile, image_url);
                    thisDropzone.options.complete.call(thisDropzone, mockFile);
                    $(".dz-preview").eq(index).attr("data-fileid", r.id);
                    $(".dz-preview").eq(index).find('.dz-crop-link').prop('href', '{{ route('admin.media.crop') }}?type=product&foreign_id=' + product_id + '&image_id=' + r.id);
                });
                $('#gallery').val(fileList.toString());
            }
        });

        myDropzone.on("sending", function (file, xhr, formData) {
            formData.append("product_id", product_id);

            $('button#SaveBtn').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> {{ __('Processing...') }}').prop('disabled', true);
        });

        myDropzone.on("success", function (file, serverFileName) {

            if (serverFileName.gallery) {
                // var get_id = serverFileName.gallery.filter(function(r){
                //   return r.name == file.upload.filename;
                // })[0];
                var get_id = serverFileName.gallery[0];
                if (get_id.id) {
                    $(file.previewElement).attr('data-fileid', get_id.id);
                    $(file.previewElement).find('.dz-crop-link').prop('href', '{{ route('admin.media.crop') }}?type=product&foreign_id=' + product_id + '&image_id=' + get_id.id);
                }
            }
        });

        myDropzone.on("complete", function (file) {

            if (file.status != 'error') {
                fileList.push(file.upload.filename);
                $('#gallery').val(fileList.toString());
            }

            $('button#SaveBtn').html($('button#SaveBtn').data('original')).prop('disabled', false);
        });

        myDropzone.on("removedfile", function (file) {

            $('button#SaveBtn').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> {{ __('Processing...') }}').prop('disabled', true);

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
                url: "{{ route('admin.products.gallery.delete') }}",
                type: "POST",
                data: "filename=" + filename + "&_token=" + token + "&product_id=" + product_id,
                cache: false,
                dataType: 'json',
                success: function (response) {
                    $('button#SaveBtn').html($('button#SaveBtn').data('original')).prop('disabled', false);
                }
            });
        });

        $("#dropzone").sortable({
            items: '.dz-preview',
            cursor: 'move',
            opacity: 0.5,
            containment: '#dropzone',
            distance: 20,
            tolerance: 'pointer',
            update: function (e, ui) {

                // show loader & disable button
                $('button#SaveBtn').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> {{ __('Processing...') }}').prop('disabled', true);

                var sortedMedia = [];
                $('#dropzone .dz-preview').each(function () {
                    sortedMedia.push($(this).data('fileid'));
                });

                // send post request
                axios.post('{{ route('admin.products.gallery.update.order') }}', {
                    items: sortedMedia,
                    id: {{ $row->id }}
                }).then(function (response) {
                    $('button#SaveBtn').html($('button#SaveBtn').data('original')).prop('disabled', false);
                });
            }
        }).disableSelection();

        $(document).ready(function () {
            $('#store_sku').keyup(function () {
                let storeSKU = $('#store_sku').val();
                let storeId = $('#store').val();
                let storeName = $( "#store option:selected" ).text();
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
    <script type="text/javascript" charset="utf-8">

        function metaTitle(id) {
            $("#meta_title" + id).val($('#title_' + id).val());
            $("#meta_keyword" + id).val($('#title_' + id).val());
        }

        function metaDesc(id) {
            $("#meta_desc" + id).val($('#data_' + id).val());
        }
    </script>
@endpush
