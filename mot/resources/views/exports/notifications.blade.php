<table style="width: 100%">
	<thead>
		<tr>
			<th>Title</th>
			<th>Customers</th>
			<th>Send at</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($notifications as $row)
		<tr>
			<td>{{ $row->title }}</td>
			<td>{{ $row->customers }}</td>
			<td>{{ $row->created_at }}</td>
		</tr>
		@endforeach
	</tbody>
</table>