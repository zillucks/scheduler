@extends('layouts.app')

@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('reservations') }}">Reservation</a></li>
        <li class="breadcrumb-item active">Reschedule</li>
    </ol>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-10">
                @if ($errors->any())
                    <div class="alert alert-error alert-dismissible" role="alert">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="card-title float-left">Booking Class - Reschedule</h5>
                        <div class="float-right">
                            <a href="{{ route('reservations') }}" class="btn btn-sm btn-danger"><i class="fas fa-times"></i></a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('reservations.reschedule.submit', $reservation->id) }}" method="POST" id="form-reschedule">
                            @csrf
                            <input type="hidden" name="_method" value="PUT">
                            <p>Selamat Datang {{ $reservation->identity->full_name }}, Silahkan mengatur ulang jadwal sesi training anda</p>
                            <div class="form-group row my-0">
                                <label for="training_name" class="col-form-label col-sm-4 col-md-3">Training</label>
                                <div class="col-sm-8">
                                    <input type="text" id="training_name" class="form-control-plaintext" value="{{ $reservation->training->training_name }}" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="location" class="col-form-label col-sm-4 col-md-3">Lokasi</label>
                                <div class="col-sm-8">
                                    <input type="text" id="location" class="form-control-plaintext" value="{{ $reservation->training->class->class_name . ' - ' . $reservation->training->site->site_name }}"
                                        readonly>
                                </div>
                            </div>
                            <div class="form-group row mt-2">
                                <label for="reservation_date" class="col-form-label col-sm-4 col-md-3">Pilih Tanggal</label>
                                <div class="col-sm-6 col-md-4">
                                    <input type="date" name="reservation_date" id="reservation_date" class="form-control {{ $errors->has('reservation_date') ? 'is-invalid' : '' }}" value="{{ $reservation->reservation_date }}" required>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <button type="button" class="btn btn-square btn-dark" id="validate-reservation-date">Validasi Tempat Duduk</button>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="reservation_time" class="col-form-label col-sm-4 col-md-3">Pilih Waktu *</label>
                                <div class="col-md-6">
                                    @foreach ($training->choosen_times as $key => $time)
                                    <div class="form-check-inline">
                                        <input type="radio" name="reservation_time" class="form-check-input" value="{{ $time->id }}" {{ $reservation->reservation_time_id == $time->id ? 'checked' : '' }}>
                                        <label class="form-check-label" for="reservation_time">{{ $time->title }}</label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="manager_email" class="col-form-label col-sm-4 col-md-3">Email Manager *</label>
                                <div class="col-sm-8">
                                    <input type="email" name="manager_email" id="manager_email" class="form-control col-sm-6 {{ $errors->has('manager_email') ? 'is-invalid' : '' }}"
                                        aria-labelledBy="manager_email_helper" value="{{ $reservation->manager_email }}" required> 
                                    <small id="manager_email_helper" class="form-text text-info">atasan anda akan menerima notif email ini untuk pemberitahuan anda akan mengikuti training</small>
                                    @if ($errors->has('manager_email'))
                                        <div class="invalid-feedback">{{ $errors->first('manager_email') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="form-group col-8">
                                    <div class="btn-group btn-group-sm">
                                        <button type="submit" class="btn btn-success" id="btn-save" disabled><i class="fas fa-save"></i> Save</button>
                                        <a href="{{ route('reservations') }}" class="btn btn-danger" id="btn-back"><i class="fas fa-arrow-left"></i> Keluar</a>
                                    </div>
                                    <small class="text-danger pl-2"><em>* Silahkan validasi tempat duduk sebelum menyimpan data</em></small>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function () {
            $('#validate-reservation-date').on('click', function () {
                var reservation_date = $('#reservation_date');
                var training_id = "{{ $reservation->training_id }}";

                if (reservation_date.val() == '') {
                    if (!reservation_date.hasClass('is-invalid')) {
                        reservation_date.addClass('is-invalid');
                        reservation_date.closest('div').append("<div class='invalid-feedback'>Pilih tanggal training yang anda inginkan</div>");
                    }
                }
                else {
                    $.ajax({
                        type: 'get',
                        url: '{{ route('reservations.validate') }}',
                        data: {training_id: training_id, reservation_date: reservation_date.val()},
                        dataType: 'json',
                        success: function (response) {
                            if (reservation_date.hasClass('is-invalid')) {
                                reservation_date.removeClass('is-invalid');
                                reservation_date.closest('div').find('.invalid-feedback').remove();
                            }

                            swal({
                                text: response.message,
                                icon: response.status,
                                dangerMode: response.status != 'success',
                                timer: 3000,
                                buttons: false
                            });

                            if (response.status == 'success') {
                                $('#btn-save').removeAttr('disabled');
                            }
                        }
                    });
                }
            })
        });
    </script>
@endsection