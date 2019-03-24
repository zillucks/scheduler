@extends('layouts.app')

@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">Scheduler</a></li>
        <li class="breadcrumb-item"><a href="{{ route('trainings.schedules') }}">Training Schedule</a></li>
        <li class="breadcrumb-item active">Participants</li>
    </ol>

    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row justify-content-center">
                <div class="col-8">
                    <div class="card">
                        <div class="card-header bg-white">
                            <h5 class="card-title float-left">Participants</h5>
                            <div class="float-right">
                                <a href="{{ route('trainings.schedules') }}" class="btn btn-sm btn-danger"><i class="fas fa-times"></i></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="get" id="form-schedules-participants">
                                <div class="form-group row">
                                    <label for="full_name" class="col-sm-3 col-md-2 col-form-label">Search</label>
                                    <div class="col-sm-6">
                                        <input type="text" name="full_name" id="full_name" class="form-control" placeholder="Cari Peserta" value="{{ request()->get('full_name') }}">
                                    </div>
                                </div>
                            </form>
                            <div class="row">
                                <div class="table-responsive table-sm">
                                    <table class="table table-bordered" id="tbl-schedules-participants">
                                        <thead>
                                            <tr>
                                                <th style="width: 60%">Full Name</th>
                                                <th style="width: 40%">Email</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($participants->count() > 0)
                                                @foreach ($participants as $participant)
                                                    <tr>
                                                        <td>{{ $participant->identity->full_name }}</td>
                                                        <td>{{ $participant->identity->email }}</td>
                                                    </tr>
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

@endsection

@section('scripts')
@endsection