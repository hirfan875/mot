
@foreach($headerCategories as $headerCategory)
    @if($headerCategory->headerSubcategories->count() > 0)
        <li>
            <a href="{{route('category', $headerCategory->slug)}}">{{$headerCategory->category_translates ? $headerCategory->category_translates->title : $headerCategory->title}}</a>
            <ul>
                <li>
                    @include('web.partials.mobile-header-all-categories', ['headerCategories' => $headerCategory->headerSubcategories])
                </li>
            </ul>
        </li>
    @else
        <li>
            <a href="{{route('category', $headerCategory->slug)}}">{{$headerCategory->category_translates ? $headerCategory->category_translates->title : $headerCategory->title}}</a>
        </li>
    @endif
@endforeach
