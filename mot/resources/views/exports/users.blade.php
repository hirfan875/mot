<table style="width: 100%">
	<thead>
		<tr>
			<th>ID</th>
			<th>Name</th>
			<th>Phone</th>
			<th>Email</th>
			<th>Points</th>
			<th>Status</th>
			<th>Created at</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($users as $row)
		<tr>
			<td>{{ $row->id }}</td>
			<td>{{ $row->name }}</td>
			<td>{{ $row->phone }}</td>
			<td>{{ $row->email }}</td>
			<td>{{ $row->total_points ?: 0 }}</td>
			<td>{{ $row->status == 1 ? 'Active' : 'Inactive' }}</td>
			<td>{{ $row->created_at }}</td>
		</tr>
		@endforeach
	</tbody>
</table>