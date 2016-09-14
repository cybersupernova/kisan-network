@extends('master')

@section('title') Contact Detail @stop

@section('styles')
<style type="text/css">
	.upper-space {
		margin-top: 10px;
	}
</style>
@stop

@section('content')
<h2 class="heading">Contact Detail</h2>
<div class="row">
	<div class="col-sm-6">
		<div class="panel panel-default">
		  	<div class="panel-heading">
		    	<h3 class="panel-title">Contact Details</h3>
		  	</div>
		  	<div class="panel-body">
		    	<div class="row">
		    		<div class="col-sm-6">First Name</div>
		    		<div class="col-sm-6">{{ $contact->first_name }}</div>
		    		<div class="col-sm-6">Last Name</div>
		    		<div class="col-sm-6">{{ $contact->last_name }}</div>
		    		<div class="col-sm-6">Phone Number</div>
		    		<div class="col-sm-6">{{ $contact->number }}</div>
		    	</div>
		  	</div>
		</div>
	</div>
	<div class="col-sm-6">
		<div class="panel panel-default">
		  	<div class="panel-heading">
		    	<h3 class="panel-title">Send Message</h3>
		  	</div>
		  	<div class="panel-body">
		    	<textarea class="form-control" id="message" readonly></textarea>
		    	<input type="hidden" id="otp" value="0">
		    	<button class="btn btn-info btn-sm upper-space" id="randomise">Randomise Number</button>
		    	<button class="btn btn-primary btn-sm pull-right upper-space" id="sendMessage">Send Message</button>
		  	</div>
		</div>
	</div>
</div>
<h3>Previous messages sent to this contact</h3>
@if(count($histories) > 0)
<table class="table table-bordered table-striped">
	<thead>
		<tr>
			<th>ID</th>
			<th>Message</th>
			<th>Status</th>
		</tr>
	</thead>
	<tbody>
		@foreach($histories as $key => $history)
		<tr>
			<td>{{ $history->id }}</td>
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
@else
<p>There are no previous messages sent to this number</p>
@endif
@stop

@section('scripts')
<script type="text/javascript">
	/*jshint multistr: true */
	function generateRandom() {
		return (""+Math.random()).substring(2,8);
	}

	function newText() {
		var number = generateRandom();
		$('#otp').val(number);
		$('#message').val('Hi. Your OTP is: ' + number);
	}
	$(function() {
		newText();
		$('#randomise').on('click', function() {
			newText();
		});

		$('#sendMessage').on('click', function() {
			var data = {_token: '{{ csrf_token() }}'};
			data.otp = $('#otp').val();
			data.message = $('#message').val();
			$.ajax({
				type: "post",
				url : "{{ url('/send/'.$contact->id) }}",
				data: data
			}).done(function(response) {
				if(response.status) {
					alert('Message sent successfully.');
				}else {
					alert(response.message);
				}
			});
		});
	});
</script>
@stop