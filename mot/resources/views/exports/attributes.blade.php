<table style="width: 100%">
	<thead>
		<tr>
			<th>Title EN</th>
			<th>Title AR</th>
			<th>Last Updated</th>
			<th>Status</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($attributes as $row)
		<tr>
			<td>{{ $row->title }}</td>
			<td>{{ $row->ar_title }}</td>
			<td>{{ $row->updated_at }}</td>
			<td>{{ $row->status == 1 ? 'Active' : 'Inactive' }}</td>
		</tr>
		@endforeach
	</tbody>
</table>