<div class="categories">

    <ul class="multi-level" role="menu" aria-labelledby="dropdownMenu">
        @foreach($headerNavcategories as $category)
        @if($category->headerSubcategories->count() > 0)
        @include('web.partials.sub-categories', ['category' => $category, 'subcategories' => $category->headerSubcategories])
        @else
        <li><a href="{{route('category', $category->slug)}}">{{$category->category_translates ? $category->category_translates->title : $category->title}}</a></li>
        @endif
        @endforeach

    </ul>
    <ul class="multi-level" role="menu" aria-labelledby="dropdownMenu">
        <li class="dropdown-submenu">
            <a tabindex="-1" href="{{route('categories')}}" ><b>{{__('More Categories')}}</b></a>
            <div class="dropdown-menu more_categores_block inner_cat_list_c" style="padding: 10px;">
                <div class="more_categories_menu inner_c_category">
                    
                    @php
                        $i=1;
                        @endphp
                    @foreach($headerNavcategories as $category)
                    <div class="cat_main">
                        @if($category->headerSubcategories->count() > 0)
                            @if($i == 1)
                                    <h3><a  href="{{route('category', $category->slug)}}">{{ $category->category_translates ? $category->category_translates->title : $category->title }}</a></h3>  

                                @endif
                                <ul>
                                @include('web.partials.more-all-categories', ['category' => $category, 'subcategories' => $category->headerSubcategories])
                                </ul>
                        @else
                            <h3><a href="{{route('category', $category->slug)}}">{{$category->category_translates ? $category->category_translates->title : $category->title}}</a></h3>
                        @endif
                        @php
                            $i=1;
                        @endphp
                        </div>
                    @endforeach
                    
                </div>
            </div>
        </li>
    </ul>
</div>
