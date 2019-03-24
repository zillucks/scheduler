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
                                <h5 class="card-title float-left">Registrasi Ulang</h5>
                            </div>
                            <div class="card-body">
                                <form method="GET" id="form-search-class">
                                    <div class="form-group row">
                                        <label for="class_name" class="col-sm-3 col-md-2 col-form-label">Search</label>
                                        <div class="col-6">
                                            <input type="text" name="class_name" id="class_name" class="form-control" placeholder="Cari Class" value="{{ request()->get('class_name') }}">
                                        </div>
                                    </div>
                                </form>
                                <div class="row">
                                    <div class="table-responsive table-sm">
                                        <table class="table table-bordered" id="tbl-class">
                                            <thead>
                                                <tr>
                                                    <th>Class Name</th>
                                                    <th>Training Name</th>
                                                    <th>Time</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if ($trainings->total() > 0)
                                                    @foreach ($trainings as $key => $training)
                                                        @foreach ($training->choosen_times as $time)
                                                            <tr>
                                                                <td>{{ $training->class->class_name }}</td>
                                                                <td>{{ $training->training_name }}</td>
                                                                <td>{{ $time->title }}</td>
                                                                <td class="text-center">
                                                                    <a href="{{ route('classes.checkin', [$training->id, $time->id]) }}" class="btn-link" title="Checkin"><i class="fas fa-check"></i> Checkin</a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <th colspan="4" class="text-bold text-danger">No data available</th>
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
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $(function () {
            //
        });
    </script>
@endsection