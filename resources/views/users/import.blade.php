@extends('layouts.app')

@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">Scheduler</a></li>
        <li class="breadcrumb-item"><a href="{{ route('users') }}">User</a></li>
        <li class="breadcrumb-item active">Import</li>
    </ol>

    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-white">
                            <h5 class="card-title float-left">Import Data User</h5>
                            <div class="float-right">
                                <a href="{{ route('users.download', 'xlsx') }}" class="btn btn-sm btn-square btn-info"><i class="fas fa-file-excel-o"></i> Download Xlsx Template</a>
                                <a href="{{ route('users') }}" class="btn btn-sm btn-danger"><i class="fas fa-times"></i></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="form-row">
                                    <div class="col-4">
                                        <input type="file" name="files" id="files" class="form-control-file" required>
                                    </div>
                                    <div class="col-2">
                                        <button type="submit" class="btn btn-square btn-block btn-success" id="btn-import">Import</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    
@endsection