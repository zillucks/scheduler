@extends('layouts.app') 
@section('content')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Scheduler</a></li>
    <li class="breadcrumb-item active">Profile</li>
</ol>

<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row justify-content-center">
            <div class="col-8">
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="card-title float-left">User Profile</h5>
                        {{-- <div class="float-right">
                            <a href="{{ route('change-password') }}" class="btn btn-sm btn-dark"><i class="fas fa-lock"></i> Change Password</a>
                        </div> --}}
                    </div>
                    <div class="card-body">
                        <form action="{{ route('profile.update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="_method" value="PUT">
                            <div class="form-group row my-0">
                                <label for="username" class="col-sm-4 col-md-3 col-form-label">Username</label>
                                <div class="col-6">
                                    <input type="text" name="username" id="username" class="form-control-plaintext" value="{{ $user->username }}" readonly>
                                </div>
                            </div>
                            
                            <div class="form-group row mt-2">
                                <label for="full_name" class="col-sm-4 col-md-3 col-form-label">Full Name</label>
                                <div class="col-6">
                                    <input type="text" name="full_name" id="full_name" class="form-control {{ $errors->has('full_name') ? 'is-invalid' : '' }}"
                                        value="{{ $user->identity->full_name }}" required>
                                    <div class="invalid-feedback">
                                        @if ($errors->has('full_name')) {{ $errors->first('full_name') }} @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label for="email" class="col-sm-4 col-md-3 col-form-label">Email</label>
                                <div class="col-6">
                                    <input type="email" name="email" id="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" value="{{ $user->identity->email }}"
                                        required>
                                </div>
                                <div class="invalid-feedback">
                                    @if ($errors->has('email')) {{ $errors->first('email') }} @endif
                                </div>
                            </div>
                            
                            <div class="form-group row my-0">
                                <label for="role_id" class="col-sm-4 col-md-3 col-form-label">Role</label>
                                <div class="col-6">
                                    <input type="text" id="role_id" class="form-control-plaintext" value="{{ $user->roles[0]->role_name }}" readonly>
                                </div>
                            </div>
                            
                            <div class="form-group row my-0">
                                <label for="site" class="col-sm-4 col-md-3 col-form-label">Site</label>
                                <div class="col-6">
                                    <input type="text" id="site" class="form-control-plaintext" value="{{ !is_null($user->identity->site) ? $user->identity->site->site_name : '-' }}"
                                        readonly>
                                </div>
                            </div>
                            
                            <div class="form-group row my-0">
                                <label for="directorate" class="col-sm-4 col-md-3 col-form-label">Directorate</label>
                                <div class="col-6">
                                    <input type="text" id="directorate" class="form-control-plaintext" value="{{ !is_null($user->identity->directorate) ? $user->identity->directorate->directorate_name : '-' }}"
                                        readonly>
                                </div>
                            </div>
                            
                            <div class="form-group row my-0">
                                <label for="organization" class="col-sm-4 col-md-3 col-form-label">Organization</label>
                                <div class="col-6">
                                    <input type="text" id="organization" class="form-control-plaintext" value="{{ !is_null($user->identity->organization) ? $user->identity->organization->organization_name : '-' }}"
                                        readonly>
                                </div>
                            </div>
                            
                            <div class="form-group row my-0">
                                <label for="department" class="col-sm-4 col-md-3 col-form-label">Department</label>
                                <div class="col-6">
                                    <input type="text" id="department" class="form-control-plaintext" value="{{ !is_null($user->identity->department) ? $user->identity->department->department_name : '-' }}"
                                        readonly>
                                </div>
                            </div>
                            
                            <div class="form-group row my-0">
                                <label for="unit" class="col-sm-4 col-md-3 col-form-label">Unit</label>
                                <div class="col-6">
                                    <input type="text" id="unit" class="form-control-plaintext" value="{{ !is_null($user->identity->unit) ? $user->identity->unit->unit_name : '-' }}"
                                        readonly>
                                </div>
                            </div>
                            
                            <div class="form-group row my-2">
                                <div class="form-group col-8">
                                    <div class="btn-group btn-group-sm">
                                        <button type="submit" class="btn btn-success" id="btn-save"><i class="fas fa-save"></i> Save</button>
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
@endsection
 
@section('scripts')
@endsection