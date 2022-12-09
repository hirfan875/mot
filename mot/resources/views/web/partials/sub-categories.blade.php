<li class="dropdown-submenu">
    <a tabindex="-1" href="{{route('category', $category->slug)}}">{{ $category->category_translates ? $category->category_translates->title : $category->title }}</a>
    <ul class="dropdown-menu">
        @foreach($subcategories as $subcategory)
            @if($subcategory->headerSubcategories->count() > 0)
                @include('web.partials.sub-categories', ['category' => $subcategory, 'subcategories' => $subcategory->headerSubcategories])
            @else
                <li><a href="{{route('category', $subcategory->slug)}}">{{ $subcategory->category_translates ? $subcategory->category_translates->title : $subcategory->title }}</a></li>
            @endif
        @endforeach
    </ul>
</li>
