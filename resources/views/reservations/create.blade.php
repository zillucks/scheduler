@extends('layouts.app')

@section('content')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Scheduler</a></li>
    <li class="breadcrumb-item"><a href="{{ route('reservations') }}">Reservation</a></li>
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
                            <h4 class="card-title float-left">Booking Class</h4>
                            <div class="float-right">
                                <a href="{{ route('reservations') }}" class="btn btn-sm btn-danger"><i class="fas fa-times"></i></a>
                            </div>
                        </div>
                        <div class="card-body">
                            @if (!isset($user->identity->site))
                            <div class="alert alert-warning alert-dismissable" role="alert">
                                Data anda belum lengkap. Silahkan hubungi tim IT untuk melengkapi data anda
                            </div>
                                
                            @else
                                {{ $user->identity->full_name }} silahkan memilih Training yang ingin anda ikuti di site {{ $user->identity->site->site_name }}
                                ////
                            @endif
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