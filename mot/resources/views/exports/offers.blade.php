<table style="width: 100%">
	<thead>
		<tr>
			<th>Position</th>
			<th>Country</th>
			<th>Offers Has Link</th>
			<th>Last Updated</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($offers as $row)
		<tr>
			<td>{{ $row->position }}</td>
			<td>{{ ( $row->country ? $row->country->title : '' ) }}</td>
			<td>{{ $row->has_link == 1 ? 'Yes' : 'No' }}</td>
			<td>{{ $row->updated_at }}</td>
		</tr>
		@endforeach
	</tbody>
</table>