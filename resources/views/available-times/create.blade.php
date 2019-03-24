@extends('layouts.app')

@section('content')

    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">Scheduler</a></li>
        <li class="breadcrumb-item"><a href="{{ route('available-times') }}">Available Time</a></li>
        <li class="breadcrumb-item active">Create</li>
    </ol>

    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row justify-content-center">
                <div class="col-6">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="card">
                        <div class="card-header bg-wite">
                            <h5 class="card-title float-left">Add Times</h5>
                            <div class="float-right">
                                <a href="{{ route('available-times') }}" class="btn btn-sm btn-danger"><i class="fas fa-times"></i></a>
                            </div>
                        </div>

                        <div class="card-body">
                            <form action="{{ route('available-times.save') }}" method="POST" id="form-add-times">
                                @csrf
                                <div class="form-group row">
                                    <label for="start_time" class="col-form-label col-sm-6 col-md-4">Start Time</label>
                                    <div class="col-sm-6 col-md-4">
                                        <input type="time" name="start_time" id="start_time" class="form-control {{ $errors->has('start_time') ? 'is-invalid' : '' }}" required value="{{ old('start_time') }}">
                                        @if ($errors->has('start_time'))
                                            <div class="invalid-feedback">{{ $errors->first('start_time') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="end_time" class="col-form-label col-sm-6 col-md-4">End Time</label>
                                    <div class="col-sm-6 col-md-4">
                                        <input type="time" name="end_time" id="end_time" class="form-control {{ $errors->has('end_time') ? 'is-invalid' : '' }}"
                                            required value="{{ old('end_time') }}"> @if ($errors->has('end_time'))
                                        <div class="invalid-feedback">{{ $errors->first('end_time') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="form-group col-8">
                                        <div class="btn-group btn-group-sm">
                                            <button type="submit" class="btn btn-success" id="btn-save"><i class="fas fa-save"></i> Save</button>
                                            <a href="{{ route('available-times') }}" class="btn btn-danger" id="btn-back"><i class="fas fa-arrow-left"></i> Back</a>
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