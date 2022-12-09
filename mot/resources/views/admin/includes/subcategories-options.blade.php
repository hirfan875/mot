@if ($subcategories)
  @foreach ($subcategories as $r)
    <option value="{{ $r->id }}" @if ($r->id == $parent_id) selected @endif>{!! getSubcategoryLevel($level, $level, '&nbsp') !!}{{ $r->category_translates ? $r->category_translates->title : $r->title }}</option>
    @include('admin.includes.subcategories-options', ['subcategories' => $r->subcategories, 'level' => $level + 1, 'parent_id' => $parent_id]);
  @endforeach
@endif
