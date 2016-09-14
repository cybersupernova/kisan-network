@extends('master')

@section('title') Message History @stop

@section('content')
<h2 class="heading">Message History</h2>
<table class="table table-bordered table-striped">
	<thead>
		<tr>
			<th>ID</th>
			<th>First Name</th>
			<th>Last Name</th>
			<th>Number</th>
			<th>Message</th>
			<th>Status</th>
		</tr>
	</thead>
	<tbody>
		@foreach($histories as $key => $history)
		<tr>
			<td>{{ $history->id }}</td>
			<td>{{ $history->first_name }}</td>
			<td>{{ $history->last_name }}</td>
			<td>{{ $history->number }}</td>
			<td>{{ $history->message }}</td>
			<td>
				@if($history->status == 0)
					Success
				@else
					Failed
				@endif
			</td>
		</tr>
		@endforeach
	</tbody>
</table>
@stop

@section('scripts')
<script type="text/javascript">
	$(function() {
		$('.nav.navbar-nav li.history').addClass('active');
	});
</script>
@stop