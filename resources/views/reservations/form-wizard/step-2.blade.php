@extends('layouts.app') 
@section('content')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Scheduler</a></li>
    <li class="breadcrumb-item"><a href="{{ route('reservations') }}">Reservation</a></li>
    <li class="breadcrumb-item active">Booking Wizard</li>
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
                            <h4 class="card-title float-left">Booking Class</h4>
                            <div class="float-right">
                                <a href="{{ route('reservations.booking-wizard', 'step=1') }}" class="btn btn-sm btn-danger"><i class="fas fa-arrow-left"></i> Back</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <p>Silahkan pilih pendaftaran dibawah ini</p>
                            <div class="btn-group btn-block btn-group-justified btn-group-toggle" data-toggle="buttons">
                                <label class="btn btn-lg btn-square btn-info py-4">
                                    <input type="radio" name="reservation_type" id="group" autocomplete="off" value="group"> Group
                                </label>
                                <label class="btn btn-lg btn-square btn-success py-4">
                                    <input type="radio" name="reservation_type" id="mandiri" autocomplete="off" value="mandiri"> Mandiri
                                </label>
                            </div>
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
            $('input[name="reservation_type"]').on('change', function () {
                var reservation_type = $(this).val();

                $.ajax({
                    type: 'post',
                    url: "{{ route('reservations.form-wizard.step-2') }}",
                    data: {reservation_type: reservation_type},
                    success: function (response) {
                        if (response.status == 'success') {
                            window.location.href = '{{ route('reservations.booking-wizard') }}';
                        }
                        else {
                            swal({
                                title: 'Error ' + response.code,
                                text: response.message,
                                dangerMode: true
                            });
                        }
                    }
                });
            })
        })
</script>
@endsection