@extends('layouts.app')

@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">Scheduler</a></li>
        <li class="breadcrumb-item"><a href="{{ route('attendances.re-registration') }}">Training</a></li>
        <li class="breadcrumb-item active">Re Registration</li>
    </ol>

    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row justify-content-center">
                <div class="col-sm-12 col-md-8">
                    <div class="card">
                        <div class="card-header bg-white">
                            <h5 class="card-title float-left">Registrasi Ulang</h5>
                            <div class="float-right">
                                <a href="{{ route('attendances.re-registration') }}" class="btn btn-sm btn-danger"><i class="fas fa-times"></i></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive table-sm">
                                <table class="table table-bordered" id="tbl-attendance-checkin">
                                    <thead>
                                        <tr>
                                            <th style="width:10%" class="text-center">
                                                <div class="form-check-inline checkbox">
                                                    <input type="checkbox" class="form-check-input" id="toggle-check-participants">
                                                    <label for="toggle-check-participants" class="form-check-label">Select</label>
                                                </div>
                                            </th>
                                            <th style="width:70%">Full Name</th>
                                            <th style="width:20%">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($attendance->participants->count() > 0)
                                            @foreach ($attendance->participants as $i => $participant)
                                                <tr class="{{ $participant->status_class }}">
                                                    <td class="text-center"><input type="checkbox" class="form-check-input check-participant" name="participant[{{ $i }}]" id="{{ $participant->id }}" value="{{ $participant->id }}"></td>
                                                    <td><label for="{{ $participant->id }}" class="form-check-label">{{ $participant->identity->full_name }}</label></td>
                                                    <td>{{ $participant->status }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                                <div class="btn-group float-left">
                                    <button class="btn btn-sm btn-square btn-success btn-checkin" value="present">Hadir</button>
                                    <button class="btn btn-sm btn-square btn-danger btn-checkin" value="absent">Tidak Hadir</button>
                                </div>
                                <button class="btn btn-sm btn-square btn-info float-right" id="btn-submit">Submit</button>
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
            var table = $('#tbl-attendance-checkin');
            $('#toggle-check-participants').on('change', function () {
                var checked = $(this).prop('checked');
                table.find('.check-participant').each(function () {
                    $(this).prop('checked', checked);
                });
            });

            $('.btn-checkin').on('click', function () {
                var presence = $(this).val();
                var attendances = table.find('.check-participant:checked').map(function (i, data) {
                    return $(this).val();
                }).get();

                $.ajax({
                    type: 'POST',
                    url: '{{ route('attendances.checkin.presence', $attendance->id) }}',
                    data: {'presence': presence, attendances: attendances},
                    dataType: 'json',
                    success: function (response) {
                        window.location.reload();
                    }
                });
            });

            $('#btn-submit').on('click', function () {
                swal({
                    title: "Submit Kehadiran",
                    text: "Setelah Submit, training dianggap selesai dan kehadiran tidak bisa diubah. Lanjutkan?",
                    icon: "warning",
                    dangerMode: true,
                    buttons: true,
                })
                .then((confirm) => {
                    if (confirm) {
                        $.ajax({
                            type: 'GET',
                            url: "{{ route('attendances.checkin.submit', $attendance->id) }}",
                            dataType: 'json',
                            success: function (response) {
                                // swal(response.message, response.status);
                                window.location.href = "{{ route('attendances.re-registration') }}";
                            }
                        });
                    }
                });
            });
        })
    </script>    
@endsection