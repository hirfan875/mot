@if ($subcategories)
  @foreach ($subcategories as $row)
  <tr>
    <td>
      <x-admin.status-switcher :id="$row->id" :value="$row->status" />
    </td>
    <td><a href="{{ route('admin.categories.edit', ['category' => $row->id]) }}">{!! getSubcategoryLevel($level, $level, '—') !!} {{ $row->category_translates ? $row->category_translates->title : $row->title }}</a></td>
    <td class="text-center">
      <x-admin.image :file="$row->image" :thumbnail="$row->getMedia('image', 'thumbnail')" />
    </td>
    <td class="text-center">
      <x-admin.image :file="$row->banner" :thumbnail="$row->getMedia('banner', 'thumbnail')" />
    </td>
    <td class="text-center">{{ $row->updated_at }}</td>
    <td class="text-center">
      <x-admin.edit-button :url="route('admin.categories.edit', ['category' => $row->id])" />
    </td>
  </tr>
  @include('admin.categories.subcategories-list', ['subcategories' => $row->subcategories, 'level' => $level + 1])
  @endforeach
@endif
