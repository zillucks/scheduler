@extends('layouts.app') 
@section('content')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Scheduler</a></li>
    <li class="breadcrumb-item"><a href="{{ route('trainings') }}">Training</a></li>
    <li class="breadcrumb-item active">Edit</li>
</ol>

<div class="flex-row align-items-center">
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row justify-content-center">
                <div class="col-10">
                    @if ($errors->any()) @foreach ($errors->all() as $error)
                    <div class="alert alert-danger" role="alert">{{ $error }}</div>
                    @endforeach @endif
                    <div class="card">
                        <div class="card-header bg-white clearfix">
                            <h4 class="card-title float-left">Edit Training</h4>
                            <div class="float-right">
                                <a href="{{ route('trainings') }}" class="btn btn-sm btn-danger"><i class="fas fa-times"></i></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('trainings.update', $training->slug) }}" method="POST" id="form-update-trainings">
                                @csrf
                                <input type="hidden" name="_method" value="PUT">
                                <div class="form-group row">
                                    <label for="training_name" class="col-sm-4 col-md-3 col-form-label">Training Name</label>
                                    <div class="col-sm-6">
                                        <input type="text" name="training_name" id="training_name" class="form-control {{ $errors->has('training_name') ? 'is-invalid' : '' }}"
                                            placeholder="Training Name" value="{{ $training->training_name }}">
                                            
                                        @if ($errors->has('training_name'))
                                            <div class="invalid-feedback">{{ $errors->first('training_name') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="site_id" class="col-sm-4 col-md-3 col-form-label">Site</label>
                                    <div class="col-sm-6">
                                        <select name="site_id" id="site_id" class="form-control {{ $errors->has('site_id') ? 'is-invalid' : '' }}">
                                            <option value="">-- Choose Site --</option>
                                            @foreach ($sites as $key => $value)
                                                <option value="{{ $key }}" {{ $training->site_id == $key ? 'selected' : '' }}>{{ $value }}</option>
                                            @endforeach
                                        </select> @if ($errors->has('site_id'))
                                        <div class="invalid-feedback">{{ $errors->first('site_id') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="class_id" class="col-sm-4 col-md-3 col-form-label">Class</label>
                                    <div class="col-sm-6">
                                        <select name="class_id" id="class_id" class="form-control {{ $errors->has('class_id') ? 'is-invalid' : '' }}">
                                            <option value="">-- Choose Class --</option>
                                            @foreach ($classes as $key => $value)
                                                <option value="{{ $key }}" {{ $training->class_id == $key ? 'selected' : '' }}>{{ $value }}</option>
                                            @endforeach
                                        </select> @if ($errors->has('class_id'))
                                        <div class="invalid-feedback">{{ $errors->first('class_id') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="start_date" class="col-sm-4 col-md-3 col-form-label">Start Date</label>
                                    <div class="col-sm-4">
                                        <input type="date" name="start_date" id="start_date" class="form-control {{ $errors->has('start_date') ? 'is-invalid' : '' }}"
                                            value="{{ $training->start_date }}"> @if ($errors->has('start_date'))
                                        <div class="invalid-feedback">{{ $errors->first('start_date') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="end_date" class="col-sm-4 col-md-3 col-form-label">End Date</label>
                                    <div class="col-sm-4">
                                        <input type="date" name="end_date" id="end_date" class="form-control {{ $errors->has('end_date') ? 'is-invalid' : '' }}"
                                            value="{{ $training->end_date }}"> @if ($errors->has('end_date'))
                                        <div class="invalid-feedback">{{ $errors->first('end_date') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="available_time" class="col-sm-4 col-md-3 col-form-label">Times</label>
                                    <div class="col-sm-8">
                                        @if ($times->count() == 0)
                                            <small class="text-danger"><em>Anda belum mengatur daftar waktu tersedia. Silahkan mengatur waktu tersedia</em></small>
                                        @else
                                            <div class="form-check">
                                                @foreach ($times as $key => $time)
                                                <div class="form-check-inline">
                                                    <input type="checkbox" name="training_available_time[{{ $key }}]" id="training_available_time{{$key}}" class="form-check-input training_status"
                                                        value="{{ $time->id }}" {{ $training->times->contains('available_time_id', $time->id) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="training_available_time{{$key}}">{{ $time->title }}</label>
                                                </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-4 col-md-3 col-form-label">Status</div>
                                    <div class="col-sm-6">
                                        <div class="form-check">
                                            <input type="checkbox" name="training_status" id="training_status" class="form-check-input" value="1" {{ $training->training_status ? 'checked' : '' }}>
                                            <label class="form-check-label" for="training_status">{{ $training->training_status ? 'Aktif' : 'Tidak Aktif' }}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="form-group col-8">
                                        <div class="btn-group btn-group-sm">
                                            <button type="submit" class="btn btn-success" id="btn-save"><i class="fas fa-save"></i> Save</button>
                                            <a href="{{ route('trainings') }}" class="btn btn-danger" id="btn-back"><i class="fas fa-arrow-left"></i> Back</a>
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
        $('#training_status').on('change', function () { if ($(this).is(':checked')) { $(this).closest('.form-check').find('.form-check-label').html('Aktif');
        } else { $(this).closest('.form-check').find('.form-check-label').html('Tidak Aktif'); } });

        $('#site_id').on('change', function () {
            var site_id = $(this).val();
            $('#class_id').load("/sites/" + site_id + "/classes", function (response) {
                $(this).html(response);
            });
        });
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