@extends('layouts.app')

@section('content')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Scheduler</a></li>
    <li class="breadcrumb-item"><a href="{{ route('sites') }}">Site</a></li>
    <li class="breadcrumb-item active">Create</li>
</ol>

<div class="flex-row align-items-center">
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row justify-content-center">
                <div class="col-8">
                    @if ($errors->any())
                        @foreach ($errors->all() as $error)
                            <div class="alert alert-danger" role="alert">{{ $error }}</div>
                        @endforeach
                    @endif
                    <div class="card">
                        <div class="card-header bg-white clearfix">
                            <h4 class="card-title float-left">Add Site</h4>
                            <div class="float-right">
                                <a href="{{ route('sites') }}" class="btn btn-sm btn-danger"><i class="fas fa-times"></i></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('sites.save') }}" method="POST" id="form-add-sites">
                                @csrf
                                <div class="row">
                                    <div class="form-group col-8">
                                        <input
                                            type = "text"
                                            name = "site_name"
                                            id = "site_name"
                                            class = "form-control {{ $errors->has('site_name') ? 'is-invalid' : '' }}"
                                            placeholder = "Site" value="{{ old('site_name') }}">
                                        
                                        @if ($errors->has('site_name'))
                                        <div class="invalid-feedback">{{ $errors->first('site_name') }}</div>
                                        @endif
                                    </div>
                                    <div class="form-group col-8">
                                        <div class="btn-group btn-group-sm">
                                            <button type="submit" class="btn btn-success" id="btn-save"><i class="fas fa-save"></i> Save</button>
                                            <a href="{{ route('sites') }}" class="btn btn-danger" id="btn-back"><i class="fas fa-arrow-left"></i> Back</a>
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