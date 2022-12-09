<table style="width: 100%">
	<thead>
		<tr>
			<th>Title</th>
            <th>Status</th>
			<th>Last Updated</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($brands as $row)
		<tr>
			<td>{{ $row->title }}</td>
			<td>{{ $row->status == 1 ? 'Active' : 'Inactive' }}</td>
            <td>{{ $row->updated_at }}</td>
		</tr>
		@endforeach
	</tbody>
</table>