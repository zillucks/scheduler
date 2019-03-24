@extends('layouts.app')

@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">Schedule</a></li>
        <li class="breadcrumb-item active">Training</li>
    </ol>

    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-white">
                            <h5 class="card-title">Registrasi Ulang</h5>
                        </div>
                        <div class="card-body">
                            <form method="GET" id="form-search-class" class="mb-4">
                                <div class="form-group row">
                                    <label for="training_name" class="col-form-label col-sm-3 col-md-2">Search</label>
                                    <div class="col-6">
                                        <input type="text" name="training_name" id="training_name" class="form-control" placeholder="Training Name" value="{{ request()->get('training_name') }}">
                                    </div>
                                </div>
                            </form>
                            <div class="table-responsive table-sm">
                                <table class="table table-bordered" id="tbl-attendance">
                                    <thead>
                                        <tr>
                                            <th>Training</th>
                                            <th>Lokasi</th>
                                            <th>Tanggal</th>
                                            <th>Waktu</th>
                                            <td>Status</td>
                                            <td>Action</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($attendances->total() > 0)
                                            @foreach ($attendances as $attendance)
                                                <tr>
                                                    <td>{{ $attendance->training->training_name }}</td>
                                                    <td>{{ $attendance->location }}</td>
                                                    <td>{{ $attendance->training_attendance_date }}</td>
                                                    <td>{{ $attendance->time->title }}</td>
                                                    <td>{!! $attendance->status !!}</td>
                                                    <td class="text-center">
                                                        @if ($attendance->canRegister())
                                                            <a href="{{ route('attendances.checkin', $attendance->id) }}" class="btn btn-sm btn-square btn-success"><i class="fas fa-check"></i>Registrasi</a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <th colspan="6" class="text-danger">No data available</th>
                                            </tr>
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

@endsection

@section('scripts')
    
@endsection