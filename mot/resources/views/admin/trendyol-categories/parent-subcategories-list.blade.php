@if ($subcategories)
@foreach ($subcategories as $row)
<tr>
    <td>{!! getSubcategoryLevel($level, $level, '—') !!} {{ $row->title }}</td>
    <td class="text-center">{{ $row->id }}</td>
    <th class="text-center" width="15%">{{ isset($row->categoriesAssign[0]) ? $row->categoriesAssign[0]->title : '' }}</th>
    <td class="text-center">
        <a href="#asignCategoryModal" data-toggle="modal" data-target="#asignCategoryModal" data-url="{{ route('admin.trendyol.categories.assign', ['trendyol' => $row->id]) }}" class="btn btn-outline-primary btn-sm mb-1">{{ __('Assign Category') }}</a>
    </td>
</tr>
 @include('admin.trendyol-categories.parent-subcategories-list', ['subcategories' => $row->childrenRecursive, 'level' => 1])
@endforeach
@endif
