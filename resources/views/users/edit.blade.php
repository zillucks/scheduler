@extends('layouts.app')

@section('content')
<ol class="breadcrumb">
	<li class="breadcrumb-item"><a href="{{ url('/') }}">Scheduler</a></li>
	<li class="breadcrumb-item"><a href="{{ route('users') }}">User</a></li>
	<li class="breadcrumb-item active">Edit</li>
</ol>

<div class="flex-row align-items-center">
	<div class="container-fluid">
		<div class="animated fadeIn">
			<div class="row justify-content-center">
				<div class="col-8">
					@include('layouts.session')
					@if ($errors->any())
						@foreach ($errors->all() as $error)
							<div class="alert alert-danger" role="alert">{{ $error }}</div>
						@endforeach
					@endif
					<div class="card">
						<div class="card-header bg-white clearfix">
							<h4 class="card-title float-left">Edit User</h4>
							<div class="float-right">
								<a href="{{ route('users') }}" class="btn btn-sm btn-danger"><i class="fas fa-times"></i></a>
							</div>
						</div>
						<div class="card-body">
							<form action="{{ route('users.update', $user->username) }}" method="POST" id="form-add-users">
                                @csrf
                                <input name="_method" type="hidden" value="PUT">
								<div class="form-group row">
									<label for="username" class="col-sm-3 col-md-2 col-form-label">Username</label>
									<div class="col-sm-4">
										<input type="text" name="username" id="username" class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}"
											value="{{ $user->username }}" placeholder="Username" readonly> @if ($errors->has('username'))
										<div class="invalid-feedback">{{ $errors->first('username') }}</div>
										@endif
									</div>
								</div>
								<div class="form-group row">
									<label for="full_name" class="col-sm-3 col-md-2 col-form-label">Fullname</label>
									<div class="col-sm-6">
										<input type="text" name="full_name" id="full_name" class="form-control {{ $errors->has('full_name') ? 'is-invalid' : '' }}"
											value="{{ !empty($user->identity) ? $user->identity->full_name : '' }}" placeholder="Full Name"> @if ($errors->has('full_name'))
										<div class="invalid-feedback">{{ $errors->first('full_name') }}</div>
										@endif
									</div>
								</div>
								<div class="form-group row">
									<label for="email" class="col-sm-3 col-md-2 col-form-label">Email</label>
									<div class="col-sm-6">
                                        <input 
                                        type="email"
                                        name="email"
                                        id="email"
                                        class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                        value="{{ !empty($user->identity) ? $user->identity->email : '' }}"
                                        placeholder="Email Address">
                                        
                                        @if ($errors->has('email'))
                                        <div class="invalid-feedback">{{ $errors->first('email') }}</div>
										@endif
									</div>
								</div>
								<div class="form-group row">
									<label for="site_id" class="col-sm-3 col-md-2 col-form-label">Site</label>
									<div class="col-sm-6">
										<select name="site_id" id="site_id" class="form-control {{ $errors->has('site_id') ? 'is-invalid' : '' }}">
                                            <option value="">-- Choose Site --</option>
                                            @foreach ($sites as $key => $value)
                                                <option value="{{ $key }}" {{ isset($user->identity->site_id) && $user->identity->site_id === $key ? 'selected' : '' }}>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                        
                                        @if ($errors->has('site_id'))
										<div class="invalid-feedback">{{ $errors->first('site_id') }}</div>
										@endif
									</div>
								</div>
								<div class="form-group row">
									<label for="directorate_id" class="col-sm-3 col-md-2 col-form-label">Directorate</label>
									<div class="col-sm-6">
										<select name="directorate_id" id="directorate_id" class="form-control {{ $errors->has('directorate_id') ? 'is-invalid' : '' }}">
                                            <option value="">-- Choose Directorate --</option>
                                            @foreach ($directorates as $key => $value)
                                                <option value="{{ $key }}" {{ isset($user->identity->directorate_id) && $user->identity->directorate_id === $key ? 'selected' : '' }}>{{ $value }}</option>
                                            @endforeach
                                        </select> @if ($errors->has('directorate_id'))
										<div class="invalid-feedback">{{ $errors->first('directorate_id') }}</div>
										@endif
									</div>
								</div>
								<div class="form-group row">
									<label for="organization_id" class="col-sm-3 col-md-2 col-form-label">Organization</label>
									<div class="col-sm-6">
										<select name="organization_id" id="organization_id" class="form-control {{ $errors->has('organization_id') ? 'is-invalid' : '' }}">
											<option value="">-- Choose Organization --</option>
											@foreach ($organizations as $key => $value)
												<option value="{{ $key }}" {{ isset($user->identity->organization_id) && $user->identity->organization_id === $key ? 'selected' : '' }}>{{ $value }}</option>
											@endforeach
										</select> @if ($errors->has('organization_id'))
										<div class="invalid-feedback">{{ $errors->first('organization_id') }}</div>
										@endif
									</div>
								</div>
								<div class="form-group row">
									<label for="department_id" class="col-sm-3 col-md-2 col-form-label">Department</label>
									<div class="col-sm-6">
										<select name="department_id" id="department_id" class="form-control {{ $errors->has('department_id') ? 'is-invalid' : '' }}">
											<option value="">-- Choose Department --</option>
											@foreach ($departments as $key => $value)
												<option value="{{ $key }}" {{ isset($user->identity->department_id) && $user->identity->department_id === $key ? 'selected' : '' }}>{{ $value }}</option>
											@endforeach
										</select>
										
										@if ($errors->has('department_id'))
											<div class="invalid-feedback">{{ $errors->first('department_id') }}</div>
										@endif
									</div>
								</div>
								<div class="form-group row">
									<label for="unit_id" class="col-sm-3 col-md-2 col-form-label">Unit</label>
									<div class="col-sm-6">
										<select name="unit_id" id="unit_id" class="form-control {{ $errors->has('unit_id') ? 'is-invalid' : '' }}">
											<option value="">-- Choose Unit --</option>
											@foreach ($units as $key => $value)
												<option value="{{ $key }}" {{ isset($user->identity->unit_id) && $user->identity->unit_id === $key ? 'selected' : '' }}>{{ $value }}</option>
											@endforeach
										</select>
										
										@if ($errors->has('unit_id'))
											<div class="invalid-feedback">{{ $errors->first('unit_id') }}</div>
										@endif
									</div>
								</div>
								<div class="form-group row">
									<div class="col-sm-3 col-md-2 col-form-label">Status</div>
									<div class="col-sm-6">
										<div class="form-check">
											<input type="checkbox" name="identity_status" id="identity_status" class="form-check-input" value="1" {{ isset($user->identity) && $user->identity->identity_status ? 'checked' : '' }}>
											<label class="form-check-label" for="identity_status">{{ isset($user->identity) && $user->identity->identity_status ? 'Aktif' : 'Tidak Aktif' }}</label>
										</div>
									</div>
								</div>
								<div class="form-group row">
									<div class="form-group col-8">
										<div class="btn-group btn-group-sm">
											<button type="submit" class="btn btn-success" id="btn-save"><i class="fas fa-save"></i> Save</button>
											<a href="{{ route('users') }}" class="btn btn-danger" id="btn-back"><i class="fas fa-arrow-left"></i> Back</a>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection

@section('scripts')
	<script>
		$(document).ready(function () {
			$('#identity_status').on('change', function () {
				if ($(this).is(':checked')) {
					$(this).closest('.form-check').find('.form-check-label').html('Aktif');
				}
				else {
					$(this).closest('.form-check').find('.form-check-label').html('Tidak Aktif');
				}
			});
		});
	</script>
@endsection