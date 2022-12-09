<div class="add_banner1 d-none d-md-block d-lg-block">
    <a href="{{$section->sortable->button_url}}" title="{{isset($section->sortable->banner_translates) ? $section->sortable->banner_translates->button_text : $section->sortable->button_text}}"><img loading="lazy" src="{{isset($section->sortable->banner_translates) ? $section->sortable->banner_translates->getMedia('image', 'original') : $section->sortable->getMedia('image', 'original')}}" alt="image"></a>
</div>

<div class="add_banner1 add_banner_mobile d-block d-md-none d-lg-none">
    <a href="{{$section->sortable->button_url}}" title="{{isset($section->sortable->banner_translates) ? $section->sortable->banner_translates->button_text : $section->sortable->button_text}}"><img loading="lazy" src="{{isset($section->sortable->banner_translates) ? $section->sortable->banner_translates->getMedia('image_mobile', 'original') : $section->sortable->getMedia('image_mobile', 'original')}}" alt="image_mobile"></a>
</div>
