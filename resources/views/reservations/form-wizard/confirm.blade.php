@extends('layouts.app')

@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">Scheduler</a></li>
        <li class="breadcrumb-item"><a href="{{ route('classes') }}">Reservation</a></li>
        <li class="breadcrumb-item active">Booking Wizard</li>
    </ol>

    <div class="flex-row align-items-center">
        <div class="container-fluid">
            <div class="animated fadeIn">
                <div class="row justify-content-center">
                    <div class="col-8">
                        <div class="card">
                            <div class="card-header bg-white">
                                <h5 class="card-title">Confirmation</h5>
                            </div>
                            <div class="card-body">
                                <p>Dear {{ $identity->full_name }},</p>
                                <p>Anda sudah terdaftar untuk Pelatihan {{ $reservation->training->training_name }} yang akan dilakukan pada :</p>
                                <div class="py-3 px-5">
                                    <div class="form-group row m-0">
                                        <label for="reservation_date" class="col-form-label col-sm-4 col-md-3">Tanggal</label>
                                        <div class="col-6">
                                            <input type="text" id="reservation_date" class="form-control-plaintext" value="{{ $reservation->reservation_date }}" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row m-0">
                                        <label for="reservation_time" class="col-form-label col-sm-4 col-md-3">Waktu</label>
                                        <div class="col-6">
                                            <input type="text" id="reservation_time" class="form-control-plaintext" value="{{ $time->title }}" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row m-0">
                                        <label for="site" class="col-form-label col-sm-4 col-md-3">Tempat</label>
                                        <div class="col-6">
                                            <input type="text" id="site" class="form-control-plaintext" value="{{ $reservation->training->class->class_name . ', ' . $reservation->training->site->site_name }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <p>Kami tunggu kedatangan Bapak / Ibu. Terima kasih.</p>
                                <div class="float-right mr-5">Directorat IT</div>
                                <div class="clearfix"></div>
                                <div class="btn-group pt-4">
                                    <a href="{{ route('reservations.print', $reservation->id) }}" class="btn btn-sm btn-square btn-primary" target="_blank"><i class="fas fa-file-pdf"></i> Print to PDF</a>
                                    <a href="{{ route('reservations') }}" class="btn btn-sm btn-square btn-danger"><i class="fas fa-arrow-left"></i> Back</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection