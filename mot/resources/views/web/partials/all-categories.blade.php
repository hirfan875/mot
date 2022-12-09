<div class="categories">
    <h1 class="pb-3 pl-2"><i class="icon-menu text-secondary mr-3"></i> {{ __('All Categories') }}</h1>
    <ul class="multi-level" role="menu" aria-labelledby="dropdownMenu">
        @foreach($categories as $category)
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
            <div class="dropdown-menu more_categores_block home_cat" style="padding: 10px;">
                <div class="more_categories_menu">
                    
                        @php
                        $i=1;
                        @endphp
                        @foreach($headerNavcategories as $key => $category)
                            <div class="cat_main">
                            @if($category->headerSubcategories->count() > 0)
                                @if($i == 1)
                                    <h3><a  href="{{route('category', $category->slug)}}">{{ $category->category_translates ? $category->category_translates->title : $category->title }}</a></h3>  

                                @endif
                                <ul>
                                    @include('web.partials.more-all-categories', ['category' => $category, 'subcategories' => $category->headerSubcategories])
                                </ul>
                            @else
                                <a href="{{route('category', $category->slug)}}"><h3>{{$category->category_translates ? $category->category_translates->title : $category->title}}</h3></a>
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

