<table style="width: 100%">
	<thead>
		<tr>
			<th>Title</th>
			<th>Price</th>
                        <th>Currency</th>
			<th>Type</th>
			<th>Last Updated</th>
			<th>Status</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($products as $row)
		<tr>
			<td>{{ $row->title }}</td>
			<td>{!! $row->display_price !!}</td>
                        <td>{{ $currencies[$row->country_id] }}</td>
			<td>{{ $row->type == 1 ? 'New Product' : 'Best Deal' }}</td>
			<td>{{ $row->updated_at }}</td>
			<td>{{ $row->status == 1 ? 'Active' : 'Inactive' }}</td>
		</tr>
		@endforeach
	</tbody>
</table>