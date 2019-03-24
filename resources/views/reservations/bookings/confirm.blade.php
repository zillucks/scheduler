@extends('layouts.app')

@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">Scheduler</a></li>
        <li class="breadcrumb-item"><a href="{{ route('classes') }}">Reservation</a></li>
        <li class="breadcrumb-item active">Create</li>
    </ol>

    <div class="flex-row align-items-center">
        <div class="container-fluid">
            <div class="animated fadeIn">
                <div class="row justify-content-center">
                    <div class="col-10">
                        <div class="card">
                            <div class="card-header bg-white clearfix">
                                <h5 class="card-title float-left">Booking Class</h5>
                                <div class="float-right">
                                    <a href="{{ route('reservations.booking.choose-group') }}" class="btn btn-sm btn-danger"><i class="fas fa-times"></i></a>
                                </div>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('reservations.save') }}" method="POST" id="form-booking-confirm">
                                    @csrf
                                    <p>{{ $user->identity->full_name }}, Silahkan melengkapi sesi training yang anda pilih pada form dibawah ini</p>
                                    @if (request()->session()->get('reservation.reservation_type') == 'group')
                                        <div class="form-group row">
                                            <label for="reservation_user" class="col-form-label col-sm-4 col-md-3">Nama Lengkap *</label>    
                                            <div class="col-sm-8">
                                                <select name="reservation_user" id="reservation_user" multiple>
                                                    @foreach ($userlists as $list)
                                                        <option value="{{ $list->id }}">{{ $list->full_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="form-group row">
                                        <label for="reservation_date" class="col-form-label col-sm-4 col-md-3">Pilih Tanggal *</label>
                                        <div class="col-sm-4 col-md-3">
                                            <input type="date" name="reservation_date" id="reservation_date" class="form-control {{ $errors->has('reservation_date') ? 'is-invalid' : '' }}"
                                                value="{{ old('reservation_date') }}" required> @if ($errors->has('reservation_date'))
                                            <div class="invalid-feedback">{{ $errors->first('reservation_date') }}</div>
                                            @endif
                                        </div>
                                        <div class="col-sm-4 col-md-3">
                                            <button type="button" class="btn btn-square btn-dark" id="validate-reservation-date">Validasi Tempat Duduk</button>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="reservation_time" class="col-form-label col-sm-4 col-md-3">Pilih Waktu *</label>
                                        <div class="col-sm-8 col-md-6">
                                            @foreach ($training->choosen_times as $key => $time)
                                            <div class="form-check-inline">
                                                <input type="radio" name="reservation_time" class="form-check-input" value="{{ $time->id }}">
                                                <label class="form-check-label" for="reservation_time">{{ $time->title }}</label>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="manager_email" class="col-form-label col-sm-4 col-md-3">Email Manager *</label>
                                        <div class="col-sm-8 col-md-6">
                                            <input type="email" name="manager_email" id="manager_email" class="form-control {{ $errors->has('manager_email') ? 'is-invalid' : '' }}"
                                                value="{{ old('manager_email') }}" required> @if ($errors->has('manager_email'))
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
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function () {
            $('#validate-reservation-date').on('click', function () {
                var reservation_date = $('#reservation_date');
                var training_id = "{{ request()->session()->get('reservation.training_id') }}";

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

                            if (response.session.validate) {
                                $('#btn-save').removeAttr('disabled');
                            }
                        }
                    });
                }
            })
        })
    </script>
@endsection