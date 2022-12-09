@foreach ($subcategories as $sub)
    <div class="custom-control custom-checkbox {{$level == 1 ? 'ml-4' : null}}">
        <input type="checkbox" class="custom-control-input" name="categories[]" id="cat_{{ $sub->id }}" value="{{ $sub->id }}"  @if ( in_array($sub->id, $selected_categories) ) checked @endif onchange="calculateMotFee()">
        <label class="custom-control-label" for="cat_{{ $sub->id }}">{{$sub->category_translates ? $sub->category_translates->title : $sub->title}}</label>
        @if($sub->subcategories->count() > 0)
            @include('admin.products.subcategoriesCheckbox',['subcategories' => $sub->subcategories, 'level' => $level+ 1])
        @endif
    </div>
@endforeach
