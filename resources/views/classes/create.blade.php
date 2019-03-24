@extends('layouts.app')

@section('content')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Scheduler</a></li>
    <li class="breadcrumb-item"><a href="{{ route('classes') }}">Class</a></li>
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
                            <h4 class="card-title float-left">Add Class</h4>
                            <div class="float-right">
                                <a href="{{ route('classes') }}" class="btn btn-sm btn-danger"><i class="fas fa-times"></i></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('classes.save') }}" method="POST" id="form-add-classes">
                                @csrf
                                <div class="form-group row">
                                    <label for="class_name" class="col-sm-3 col-md-2 col-form-label">Class Name</label>
                                    <div class="col-sm-6">
                                        <input type="text" name="class_name" id="class_name" class="form-control {{ $errors->has('class_name') ? 'is-invalid' : '' }}"
                                            placeholder="Class"> @if ($errors->has('class_name'))
                                        <div class="invalid-feedback">{{ $errors->first('class_name') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="site_id" class="col-sm-3 col-md-2 col-form-label">Site</label>
                                    <div class="col-sm-6">
                                        <select name="site_id" id="site_id" class="form-control {{ $errors->has('site_id') ? 'is-invalid' : '' }}">
                                                                            <option value="">-- Choose Site --</option>
                                                                            @foreach ($sites as $key => $value)
                                                                                <option value="{{ $key }}">{{ $value }}</option>
                                                                            @endforeach
                                                                        </select> @if ($errors->has('site_id'))
                                        <div class="invalid-feedback">{{ $errors->first('site_id') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="max_quotes" class="col-sm-3 col-md-2 col-form-label">Max Class</label>
                                    <div class="col-sm-6 col-md-4 input-group">
                                        <input type="number" name="max_quotes" id="max_quotes" class="form-control {{ $errors->has('max_quotes') ? 'is-invalid' : '' }}">
                                        <div class="input-group-append">
                                            <div class="input-group-text">Orang</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-3 col-md-2 col-form-label">Status</div>
                                    <div class="col-sm-6">
                                        <div class="form-check">
                                            <input type="checkbox" name="class_status" id="class_status" class="form-check-input" value="1" checked>
                                            <label class="form-check-label" for="class_status">Aktif</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="form-group col-8">
                                        <div class="btn-group btn-group-sm">
                                            <button type="submit" class="btn btn-success" id="btn-save"><i class="fas fa-save"></i> Save</button>
                                            <a href="{{ route('classes') }}" class="btn btn-danger" id="btn-back"><i class="fas fa-arrow-left"></i> Back</a>
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
            $('#class_status').on('change', function () {
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