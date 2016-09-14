@extends('master')

@section('title') Contact Management @stop

@section('content')
<h2 class="heading">Contact Management</h2>
<a class="btn btn-info pull-right" href="#contactModal" data-toggle="modal" data-target="#contactModal">Add Contacts</a>
<table class="table table-bordered table-striped">
	<thead>
		<tr>
			<th>ID</th>
			<th>First Name</th>
			<th>Last Name</th>
			<th>Number</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		@foreach($contacts as $key => $contact)
		<tr data-id="{{ $contact->id }}">
			<td>{{ $contact->id }}</td>
			<td>{{ $contact->first_name }}</td>
			<td>{{ $contact->last_name }}</td>
			<td>{{ $contact->number }}</td>
			<td>
				<a class="btn btn-info btn-xs" href="{{ url('/contact/'.$contact->id) }}"><span class="glyphicon glyphicon-envelope"></span></a>
			</td>
		</tr>
		@endforeach
	</tbody>
</table>
@stop

@section('scripts')
<script type="text/javascript">
	/*jshint multistr: true */
	function validate() {
		if($('#first_name').val() === '') {
			setError($('#first_name'), 'First Name is required.');
			return false;
		}else {
			unsetError($('#first_name'));
		}
		if($('#last_name').val() === '') {
			setError($('#last_name'), 'Last Name is required.');
			return false;
		}else {
			unsetError($('#last_name'));
		}
		if($('#number').val() === '') {
			setError($('#number'), 'Phone Number is required.');
			return false;
		}else {
			unsetError($('#number'));
		}
		var regex = /^\+[\d]{7,12}$/;
		if(!regex.test($('#number').val())) {
			setError($('#number'), 'Please provide a valide phone number.');
			return false;
		}else {
			unsetError($('#number'));
		}
		return true;
	}

	function saveContact(data) {
		$.ajax({
			type: "post",
			url : "{{ url('/contact/create') }}",
			data: data
		}).done(function(response) {
			if(response.status) {
				var newContacts = response.created;
				var html = '';
				$.each(newContacts, function(i, contact) {
					html += '<tr>\
								<td>' + contact.id + '</td>\
								<td>' + contact.first_name + '</td>\
								<td>' + contact.last_name + '</td>\
								<td>' + contact.number + '</td>\
								<td>\
									<a class="btn btn-info btn-xs" href="' + rootUrl + '/contact/' + contact.id + '"><span class="glyphicon glyphicon-envelope"></span></a>\
								</td>\
							</tr>';
				});
				$('table.table tbody').prepend(html);
				$('#contactModal').find('input[type="text"]').val('');
				$('#contactModal').modal('hide');
			}else {
				alert(response.message);
			}
		});
	}

	$(function() {
		$('.nav.navbar-nav li.contact').addClass('active');

		$('#createContact').on('click', function() {
			if(validate()) {
				var data = {_token: '{{ csrf_token() }}'};
				data.contacts = [];
				var tmp = {};
				tmp.first_name = $('#first_name').val();
				tmp.last_name = $('#last_name').val();
				tmp.number = $('#number').val();
				data.contacts.push(tmp);
				saveContact(data);
			}
		});

		$('#jsonCreateContact').on('click', function() {
			var json = $('#jsonArea').val();
			try {
				unsetError($('#jsonArea'));
				var contacts = JSON.parse(json);
				var data = {_token: '{{ csrf_token() }}'};
				data.contacts = contacts;
				saveContact(data);
			}catch(e) {
				setError($('#jsonArea'), 'Please provide a valid JSON');
			}
		});
	});
</script>
@stop

@section('modals')
<div id="contactModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Create new Contact</h4>
			</div>
			<div class="modal-body">
				<ul class="nav nav-tabs" role="tablist">
					<li role="presentation" class="active"><a href="#manual" aria-controls="manual" role="tab" data-toggle="tab">Create Manually</a></li>
					<li role="presentation"><a href="#json" aria-controls="json" role="tab" data-toggle="tab">Paste JSON</a></li>
				</ul>
				<div class="tab-content">
					<div role="tabpanel" class="tab-pane active" id="manual">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>First Name</label>
									<input type="text" id="first_name" class="form-control" placeholder="First Name">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Last Name</label>
									<input type="text" id="last_name" class="form-control"  placeholder="Last Name">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Phone Number</label>
									<input type="text" id="number" class="form-control"  placeholder="+91XXXXXXXXXX">
								</div>
							</div>
							<div class="col-md-6">
								<label>&nbsp;</label>
								<button class="btn btn-primary btn-block" id="createContact">Create</button>
							</div>
						</div>	
					</div>
					<div role="tabpanel" class="tab-pane" id="json">
						<div class="row">
							<div class="col-sm-12">
								<div class="form-group">
									<label for="jsonArea">Paste Your JSON</label>
									<textarea id="jsonArea" class="form-control" placeholder='[ {"first_name": "Kisan", "last_name": "Network", "number": "+919111011382"} ]'></textarea>
								</div>
							</div>
							<div class="col-sm-12">
								<div class="form-group">
									<button class="btn btn-info btn-block" id="jsonCreateContact">Create</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
@stop