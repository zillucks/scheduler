@extends('layouts.app')

@section('content')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Scheduler</a></li>
    <li class="breadcrumb-item"><a href="{{ route('directorates') }}">Directorate</a></li>
    <li class="breadcrumb-item active">Edit</li>
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
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible" role="alert">{!! session('error') !!}</div>
                    @endif
                    <div class="card">
                        <div class="card-header bg-white clearfix">
                            <h4 class="card-title float-left">Edit Directorate</h4>
                            <div class="float-right">
                                <a href="{{ route('directorates') }}" class="btn btn-sm btn-danger"><i class="fas fa-times"></i></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('directorates.update', $directorate->slug) }}" method="POST" id="form-update-directorates">
                                <input name="_method" type="hidden" value="PUT">
                                @csrf
                                <div class="row">
                                    <div class="form-group col-8">
                                        <input 
                                            type="text" 
                                            name="directorate_name" 
                                            id="directorate_name"
                                            class="form-control {{ $errors->has('directorate_name') ? 'is-invalid' : '' }}" 
                                            value="{{ $directorate->directorate_name }}" 
                                            placeholder="Directorate">
                                        @if($errors->has('directorate_name'))
                                            <div class="invalid-feedback">{{ $errors->first('directorate_name') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-8">
                                        <div class="form-check">
                                            <input type="checkbox" name="directorate_status" class="form-check-input" id="directorate_status" value=1 {!! $directorate->directorate_status ? 'checked' : '' !!}>
                                            <label class="form-check-label" for="directorate_status" id="label-directorate_status">{!! $directorate->directorate_status ? 'Aktif' : 'Tidak Aktif' !!}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-8">
                                        <div class="btn-group btn-group-sm">
                                            <button type="submit" class="btn btn-success" id="btn-update"><i class="fas fa-save"></i> Update</button>
                                            <a href="{{ route('directorates') }}" class="btn btn-danger" id="btn-back"><i class="fas fa-arrow-left"></i> Back</a>
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
        jQuery(document).ready(function () {
            $('#directorate_status').on('change', function () {
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