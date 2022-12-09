<table style="width: 100%">
	<thead>
		<tr>
			<th>Keyword</th>
			<th>No. of times searched</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($searches as $row)
		<tr>
			<td>{{  str_replace("=","",$row->keyword) }}</td>
			<td>{{ $row->total }}</td>
		</tr>
		@endforeach
	</tbody>
</table>