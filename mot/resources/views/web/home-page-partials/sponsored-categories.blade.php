<div class="container three_col">
    <div class="row">
        @foreach($section->sortable->categories as $sponsoredCategory)
            <div class="col-md-4">
                <div class="col1">
                    <a href="{{$sponsoredCategory->button_url}}"><img loading="lazy" src="{{isset($sponsoredCategory->sponsor_category_translates) ? $sponsoredCategory->sponsor_category_translates->media_image(\App\Models\SponsorCategory::SPONSOR_CATEGORY) : $sponsoredCategory->media_image(\App\Models\SponsorCategory::SPONSOR_CATEGORY)}}" alt="sponsoredCategory" class="w-100"></a>
                </div>
            </div>
        @endforeach
    </div>
</div>
