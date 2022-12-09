@if ($subcategories)
<ul class="children dd_list">
  @foreach ($subcategories as $row)
  <li data-id="{{ $row->id }}">
      @if($row->status)
        <div class="item">
          <span>{{ $row->category_translates ? $row->category_translates->title : $row->title }}</span>
        </div>
      @endif
    @include('admin.includes.subcategories-sorting', ['subcategories' => $row->subcategories])
  </li>
  @endforeach
</ul>
@endif
