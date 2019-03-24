@extends('layouts.app') 
@section('content')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Scheduler</a></li>
    <li class="breadcrumb-item"><a href="{{ route('units') }}">Unit</a></li>
    <li class="breadcrumb-item active">Import</li>
</ol>

<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row justify-content-center">
            <div class="col-12">
                <form method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card">
                        <div class="card-header bg-white">
                            <h5 class="card-title float-left">Import Data Units</h5>
                            <div class="float-right">
                                <a href="{{ route('units.download', 'xlsx') }}" class="btn btn-sm btn-square btn-info"><i class="fas fa-file-excel-o"></i> Download Xlsx Template</a>
                                <a href="{{ route('units') }}" class="btn btn-sm btn-danger"><i class="fas fa-times"></i></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-row">
                                <div class="col-6">
                                    <div class="custom-file">
                                        <input type="file" name="files" id="files" class="custom-file-input">
                                        <label for="files" class="custom-file-label">Pilih File</label>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <button type="submit" class="btn btn-square btn-block btn-success" id="btn-import">Import</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
 
@section('scripts')
@endsection