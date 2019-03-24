<!DOCTYPE html>

<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
	<meta name="author" content="zillucks">

	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>Scheduler - Login Page</title>

	<!-- Fonts -->
	<link rel="dns-prefetch" href="//fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

	<!-- Styles -->
	<link href="{{ asset('css/app.css') }}" rel="stylesheet">

</head>

<body class="app flex-row align-items-center">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-6">
				<div class="card border-danger">
					<div class="card-header bg-danger text-center">
						<h1>Login</h1>
						<small>Sign In to your account</small>
					</div>
					<div class="card-body">
						{!! Form::open(['method' => 'post', 'route' => 'login', 'class' => 'needs-validation', 'id' => 'login-form', 'novalidate']) !!}
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<span class="input-group-text">
									<i class="far fa-user"></i>
								</span>
							</div>

							@php
							$valid_state_username = $errors->has('username') ? 'is-invalid' : '';
							@endphp
							{!! Form::text('username', old('username'), ['class' => "form-control {$valid_state_username}", 'placeholder' => 'Username', 'required']) !!}
							@if ($errors->has('username'))
							<div class="invalid-feedback" role="alert">
								{{ $errors->first('username') }}
							</div>
							@endif
						</div>
						<div class="input-group mb-4">
							<div class="input-group-prepend">
								<span class="input-group-text">
									<i class="fa fa-lock"></i>
								</span>
							</div>
							@php
							$valid_state_password = $errors->has('password') ? 'is-invalid' : ''; 
							@endphp
							{!! Form::password('password', ['class' => "form-control {$valid_state_password}", 'placeholder' => 'Password']) !!}
							@if ($errors->has('password'))
								<div class="invalid-feedback" role="alert">
									{{ $errors->first('password') }}
								</div>
							@endif
						</div>
						<div class="row">
							<div class="col-6 offset-3">
								<button class="btn btn-success px-4 btn-block" type="submit">Login</button>
							</div>
						</div>
						{!! Form::close() !!}
					</div>
				</div>
			</div>
		</div>
	</div>

	<script src="{{ asset('js/app.js') }}"></script>
	<script>
		$('#ui-view').ajaxLoad(); $(document).ajaxComplete(function() { Pace.restart() });
	</script>
	<script src="{{ asset('js/sweetalert.min.js') }}"></script>
	@include('sweet::alert')
</body>

</html>