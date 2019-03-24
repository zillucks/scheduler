@extends('layouts.app')

@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">Scheduler</a></li>
        <li class="breadcrumb-item"><a href="{{ route('users') }}">User</a></li>
        <li class="breadcrumb-item active">Setting</li>
    </ol>

    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row justify-content-center">
                <div class="col-8">
                    <div class="card">
                        <div class="card-header bg-white">
                            <h5 class="card-title float-left">User Setting</h5>
                            <div class="float-right">
                                <a href="{{ route('users') }}" class="btn btn-sm btn-danger"><i class="fas fa-arrow-left"></i> Back</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('users.setting.submit', $user->username) }}" method="post">
                                @csrf
                                <div class="form-group row">
                                    <label for="username" class="col-sm-4 col-md-3 col-form-label">Username</label>
                                    <div class="col-6">
                                        <input type="text" name="username" id="username" class="form-control-plaintext" value="{{ $user->username }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="full_name" class="col-sm-4 col-md-3 col-form-label">Full Name</label>
                                    <div class="col-6">
                                        <input type="text" name="full_name" id="full_name" class="form-control-plaintext" value="{{ $user->identity->full_name }}"
                                            readonly>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label for="current_password" class="col-sm-4 col-md-3 col-form-label">Current Password</label>
                                    <div class="col-6">
                                        <input type="password" name="current_password" id="current_password" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="password" class="col-sm-4 col-md-3 col-form-label">Password</label>
                                    <div class="col-6">
                                        <input type="password" name="password" id="password" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="password_confirmation" class="col-sm-4 col-md-3 col-form-label">Confirm Password</label>
                                    <div class="col-6">
                                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="role_id" class="col-sm-4 col-md-3 col-form-label">Role</label>
                                    <div class="col-6">
                                        <select name="role_id" id="role_id" class="form-control">
                                            <option value="">Pilih Role</option>
                                            @foreach ($role_lists as $id => $value)
                                                <option value="{{ $id }}" {{ in_array($id, $user->current_roles) ? 'selected' : '' }}>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="btn-group btn-group-sm">
                                        <button type="submit" class="btn btn-square btn-success" id="btn-submit"><i class="fas fa-save"></i> Save</button>
                                        <a href="{{ route('users') }}" class="btn btn-square btn-danger" id="btn-back"><i class="fas fa-arrow-left"></i> Back</a>
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