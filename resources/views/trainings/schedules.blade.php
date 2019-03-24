@extends('layouts.app')

@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">Scheduler</a></li>
        <li class="breadcrumb-item active">Training Schedule</li>
    </ol>

    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-white">
                            <h5 class="card-title float-left">Available Schedule</h5>
                            <div class="float-right">
                                {{-- right menu button --}}
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="get" id="form-search-schedule">
                                <div class="form-group row">
                                    <label for="training_name" class="col-sm-3 col-md-2 col-form-label">Search</label>
                                    <div class="col-sm-6">
                                        <input type="text" name="training_name" id="training_name" class="form-control" placeholder="Cari Training" value="{{ request()->get('training_name') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="training_attendance_date" class="col-sm-3 col-md-2 col-form-label">Tanggal Training</label>
                                    <div class="col-sm-6">
                                        <input type="date" name="training_attendance_date" id="training_attendance_date" class="form-control" placeholder="Cari Tanggal" value="{{ request()->get('training_attendance_date') }}">
                                    </div>
                                </div>
                            </form>
                            <div class="row">
                                <div class="table-responsive table-sm">
                                    <table class="table table-bordered" id="tbl-training-schedule">
                                        <thead>
                                            <tr>
                                                <th style="width: 35%">Nama Training</th>
                                                <th style="width: 25%">Lokasi</th>
                                                <th style="width: 15%">Tanggal</th>
                                                <th style="width: 10%">Peserta</th>
                                                <th style="width: 15%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($schedules->total() > 0)
                                                @foreach ($schedules as $schedule)
                                                    <tr class="{{ $schedule->status_class }}">
                                                        <td>{{ $schedule->training->training_name }}</td>
                                                        <td>{{ $schedule->training->location }}</td>
                                                        <td>{{ $schedule->training_attendance_date }}</td>
                                                        <td>{{ $schedule->participants_count }} <small class="{{ $schedule->training_attendance_status ? 'text-white' : 'text-info' }}"><em>({{ $schedule->training->max_quote }})</em></small></td>
                                                        <td class="text-center">
                                                            <a href="{{ route('trainings.schedules.participants', $schedule->id) }}" class="btn-link {{ $schedule->training_attendance_status ? 'text-white' : 'text-info' }}" title="Edit Data"><i class="fas fa-users"></i> Participants</a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
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

@endsection

@section('scripts')
@endsection