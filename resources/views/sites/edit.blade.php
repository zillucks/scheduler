@extends('layouts.app')

@section('content')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Scheduler</a></li>
    <li class="breadcrumb-item"><a href="{{ route('sites') }}">Site</a></li>
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
                            <h4 class="card-title float-left">Edit Site</h4>
                            <div class="float-right">
                                <a href="{{ route('sites') }}" class="btn btn-sm btn-danger"><i class="fas fa-times"></i></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('sites.update', $site->slug) }}" method="POST" id="form-update-sites">
                                <input name="_method" type="hidden" value="PUT">
                                @csrf
                                <div class="row">
                                    <div class="form-group col-8">
                                        <input 
                                            type="text" 
                                            name="site_name" 
                                            id="site_name"
                                            class="form-control {{ $errors->has('site_name') ? 'is-invalid' : '' }}" 
                                            value="{{ $site->site_name }}" 
                                            placeholder="Site">
                                        @if($errors->has('site_name'))
                                            <div class="invalid-feedback">{{ $errors->first('site_name') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-8">
                                        <div class="form-check">
                                            <input type="checkbox" name="site_status" class="form-check-input" id="site_status" value=1 {!! $site->site_status ? 'checked' : '' !!}>
                                            <label class="form-check-label" for="site_status" id="label-site_status">{!! $site->site_status ? 'Aktif' : 'Tidak Aktif' !!}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-8">
                                        <div class="btn-group btn-group-sm">
                                            <button type="submit" class="btn btn-success" id="btn-update"><i class="fas fa-save"></i> Update</button>
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

@section('scripts')
    <script>
        jQuery(document).ready(function () {
            $('#site_status').on('change', function () {
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