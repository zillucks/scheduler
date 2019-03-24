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
                                <div class="row align-items-center justify-content-center pt-2">
                                    @if ($trainings->count() == 0)
                                    <div class="alert alert-warning alert-dismissible">Tidak ada training untuk site {{ $user->identity->site->site_name }}</div>
                                    @else
                                    <table class="table table-sm table-borderless table-striped">
                                        <thead class="bg-dark">
                                            <tr>
                                                <th colspan="3">Training Tersedia</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($trainings as $training)
                                            <tr data-id="{{ $training->id }}">
                                                <td style="width: 50%">{{ $training->training_name }}</td>
                                                <td style="width: 30%">{{ $training->start_date }}</td>
                                                <td style="width: 20%">
                                                    <button class="btn btn-sm btn-square btn-info btn-block select-training">Pilih</button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @endif
                                </div>
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
        $(function () {
            $('.select-training').on('click', function () {
                var rows = $(this).closest('tr');
                var id = rows.data('id');
                $.ajax({
                    type: 'post',
                    url: "{{ route('reservations.form-wizard.choose-training') }}",
                    data: {training_id: id},
                    success: function (response) {
                        if (response.status == 'success') {
                            swal({
                                text: 'Success',
                                icon: 'success',
                                timer: 2000,
                                buttons: false,
                            })
                            .then(function () {
                                window.location.href = "{{ route('reservations.booking.choose-group') }}";
                            });
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
            });
        })
    </script>
@endsection