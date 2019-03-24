@extends('layouts.app')

@section('content')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Scheduler</a></li>
    <li class="breadcrumb-item"><a href="{{ route('directorates') }}">Directorate</a></li>
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
                            <h4 class="card-title float-left">Add Directorate</h4>
                            <div class="float-right">
                                <a href="{{ route('directorates') }}" class="btn btn-sm btn-danger"><i class="fas fa-times"></i></a>
                            </div>
                        </div>
                        <div class="card-body">
                            {!! Form::open(['method' => 'post', 'route' => 'directorates.save', 'id' => 'form-add-directorates']) !!}
                            <div class="row">
                                <div class="form-group col-8">
                                    {!! Form::text('directorate_name', old('directorate_name'), ['class' => $errors->has('directorate_name') ? 'form-control is-invalid' : 'form-control',
                                    'placeholder' => 'Directorate']) !!}
                                    @if ($errors->has('directorate_name'))
                                        <div class="invalid-feedback">{{ $errors->first('directorate_name') }}</div>
                                    @endif
                                </div>
                                <div class="form-group col-8">
                                    <div class="btn-group btn-group-sm">
                                        <button type="submit" class="btn btn-success" id="btn-save">
                                            <i class="fas fa-save"></i> Save
                                        </button>
                                        <a href="{{ route('directorates') }}" class="btn btn-danger" id="btn-back">
                                            <i class="fas fa-arrow-left"></i> Back
                                        </a>
                                    </div>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection