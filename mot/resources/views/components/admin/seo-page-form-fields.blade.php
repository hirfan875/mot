<h3>{{ __('SEO Settings') }}</h3>
@foreach(getLocaleList() as $row)
    @php
        $row_id = $row->id;
        if($page_translate){
            foreach($page_translate as $val){
                if($row->id==$val->language_id) {
                     $meta_title=$val->meta_title;
                     $meta_desc=$val->meta_desc;
                     $meta_keyword=$val->meta_keyword;
                     $row_id = $val->id;
                }
            }
        }
    @endphp
    <div class="form-group">
        <label for="meta_title">{{ __('Meta Title ('.$row->title.')') }}</label>
        <input type="text" name="meta_title[{{$row->id}}]" id="meta_title{{$row->id}}" class="form-control" value="{{ old('meta_title'.$row->id, $meta_title) }}">
    </div>
    <div class="form-group">
        <label for="meta_desc">{{ __('Meta Description ('.$row->title.')') }}</label>
        <input type="text" name="meta_desc[{{$row->id}}]" id="meta_desc{{$row->id}}" class="form-control" value="{{ old('meta_desc'.$row->id, $meta_desc) }}">
    </div>
    <div class="form-group">
        <label for="meta_keyword">{{ __('Meta Keyword ('.$row->title.')') }}</label>
        <input type="text" name="meta_keyword[{{$row->id}}]" id="meta_keyword{{$row->id}}" class="form-control" value="{{ old('meta_keyword'.$row->id, $meta_keyword) }}">
    </div>
@endforeach
