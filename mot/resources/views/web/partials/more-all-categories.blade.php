
    @if($i == 1)
    
    @else
    <li><a  href="{{route('category', $category->slug)}}">{{ $category->category_translates ? $category->category_translates->title : $category->title }}</a></li>/  
    @endif
@php
    $i=0;
@endphp

@foreach($subcategories as $subcategory)
    @if($subcategory->headerSubcategories->count() > 0)
                @include('web.partials.more-all-categories', ['category' => $subcategory, 'subcategories' => $subcategory->headerSubcategories])
    @else
                <li> <a href="{{route('category', $subcategory->slug)}}">{{ $subcategory->category_translates ? $subcategory->category_translates->title : $subcategory->title }}</a></li>/  
    @endif
@endforeach
