<!DOCTYPE html>
<html>
<head>
	<title>@yield('title') - Kisan Network</title>
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<style>
		.heading {
			display: inline-block;
		}
	</style>
	@yield('styles')
</head>
<body>
	<nav class="navbar navbar-default navbar-static-top">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#">Kisan Network</a>
			</div>
			<div id="navbar" class="navbar-collapse collapse">
				<ul class="nav navbar-nav">
					<li class="contact"><a href="{{ url('/contact') }}">Manage Contacts</a></li>
					<li class="history"><a href="{{ url('/history') }}">History</a></li>
				</ul>
			</div>
		  </div>
		</nav>
	<div class="container">
		@yield('content')
	</div>
	@yield('modals')
</body>
<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script type="text/javascript">
	var rootUrl = "{{ url('/') }}";
	function setError(element, text) {
		unsetError(element);
		var parent = element.parents('.form-group:eq(0)');
		parent.addClass('has-error');
		parent.append('<span class="help-block">' + text + '</span>');
	}

	function unsetError(element) {
		var parent = element.parents('.form-group:eq(0)');
		parent.find('.help-block').remove();
		parent.removeClass('has-error');
	}
</script>
@yield('scripts')
</html>