@extends('layouts.app')

@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">Scheduler</a></li>
        <li class="breadcrumb-item"><a href="{{ route('classes') }}">Reservation</a></li>
        <li class="breadcrumb-item active">Re-Registration</li>
    </ol>

    <div class="flex-row align-items-center">
        <div class="container-fluid">
            <div class="animated fadeIn">
                <div class="row justify-content-center">
                    <div class="col-10">
                        <div class="card">
                            <div class="card-header bg-white">
                                <h5 class="card-title float-left">Absensi Kehadiran</h5>
                                <div class="float-right">
                                    <a href="{{ route('classes.re-registration') }}" class="btn btn-sm btn-danger"><i class="fas fa-times"></i></a>
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="GET" id="form-search-class">
                                    <div class="form-group row">
                                        <label for="full_name" class="col-sm-3 col-md-2 col-form-label">Search</label>
                                        <div class="col-6">
                                            <input type="text" name="full_name" id="full_name" class="form-control" placeholder="Masukkan Nama" value="{{ request()->get('full_name') }}">
                                        </div>
                                    </div>
                                </form>
                                <div class="row">
                                    <div class="table-responsive table-sm">
                                        <table class="table table-striped" id="tbl-attendance">
                                            <thead>
                                                <tr>
                                                    <th>Select</th>
                                                    <th>Full Name</th>
                                                    <th>Training</th>
                                                    <th>Site</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if ($class->reservations->count() > 0)
                                                    @foreach ($class->reservations as $reservation)
                                                        @foreach ($reservation->reservation_users as $user)
                                                            <tr>
                                                                <td>select</td>
                                                                <td>{{ $user->identity->full_name }}</td>
                                                                <td>{{ $reservation->training->training_name }}</td>
                                                                <td>{{ $reservation->training->site->site_name }}</td>
                                                            </tr>
                                                        @endforeach
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection