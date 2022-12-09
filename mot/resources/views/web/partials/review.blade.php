<li>
    <div class="comment_img">
        @if($review->customer->image)
        <img  id="profileImage1" loading="lazy" src="{{asset('storage/'.$review->customer->image)}}" alt="{{ getAvatarCode($review->customer->name) }}" height="50" width="50" />
        @else
        <span class="avatar-in" > {{ getAvatarCode($review->customer->name) }}</span>
        @endif
    </div>
    <div class="comment_block">
        <div class="rating_wrap">
            <div class="rating">
                <div class="product_rate" style="width:80%"></div>
            </div>
        </div>
        <p class="customer_meta">
            <span class="review_author">{{$review->customer->name}}</span>
            <span class="comment-date">{{$review->created_at->format('M d, Y')}}</span>
        <div class="stars">
            <span class="fa fa-star {{$review->rating >= 1 ? 'checked' : null}}"></span>
            <span class="fa fa-star {{$review->rating >= 2 ? 'checked' : null}}"></span>
            <span class="fa fa-star {{$review->rating >= 3 ? 'checked' : null}}"></span>
            <span class="fa fa-star {{$review->rating >= 4 ? 'checked' : null}}"></span>
            <span class="fa fa-star {{$review->rating >= 5 ? 'checked' : null}}"></span>
        </div>
    </p>
        <div class="description">
            <p>{{$review->comment}}</p>
        </div>
        <div class="description">
            <p>@foreach($review->gallery as $row)
                @if($row->image != null)
                <img loading="lazy" src="{{ asset('/storage/original/'.$row->image) }}" alt="{{ $row->image }}" width="60" />
                @endif
                @endforeach
            </p>
        </div>

    </div>
</li>
