@extends('layouts.app')

@section('content')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Scheduler</a></li>
    <li class="breadcrumb-item active">Training</li>
</ol>
<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="card-title float-left">Training</h5>
                        <div class="float-right">
                            <a href="{{ route('trainings.create') }}" class="btn btn-sm btn-success"><i class="fas fa-plus"></i> Tambah</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="GET" id="form-search-training">
                            <div class="form-group row">
                                <label for="training_name" class="col-sm-3 col-md-2 col-form-label">Search</label>
                                <div class="col-6">
                                    <input type="text" name="training_name" id="training_name" class="form-control" placeholder="Cari Training" value="{{ request()->get('training_name') }}">
                                </div>
                            </div>
                        </form>
                        <div class="row">
                            <div class="table-responsive table-sm">
                                <table class="table table-bordered" id="tbl-training">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 45%">Nama Training</th>
                                            <th class="text-center" style="width: 20%">Class</th>
                                            <th class="text-center" style="width: 10%">Max Student</th>
                                            <th class="text-center" style="width: 10%">Status</th>
                                            <th class="text-center" style="width: 15%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($trainings->total() > 0) @foreach ($trainings as $i => $training)
                                        <tr>
                                            <td>{{ $training->training_name }}</td>
                                            <td>{{ $training->class->class_name }}</td>
                                            <td>{{ $training->class->max_quotes }}</td>
                                            <td>{!! $training->status !!}</td>
                                            <td class="text-center">
                                                <a href="{{ route('trainings.edit', $training->slug) }}" class="btn-link" title="Edit Data">
                                                                                                        <i class="fas fa-edit"></i> Edit
                                                                                                    </a>
                                                                                                    &nbsp;
                                                <a href="{{ route('trainings.delete', $training->slug) }}" class="btn-link text-danger delete-training" title="Delete Data">
                                                                                                        <i class="fas fa-trash"></i> Hapus
                                                                                                    </a>
                                            </td>
                                        </tr>
                                        @endforeach @else
                                        <tr>
                                            <th colspan="6" class="text-danger"><em>No data available</em></th>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                                @if ($trainings->total() > 0) {!! $trainings->links() !!} @endif
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
        var tbl = $('#tbl-training');
        
        tbl.find('.delete-training').on('click', function (e) {
            e.preventDefault();
            var url = $(this).attr('href');
            
            swal({
                title: "Hapus Data",
                text: "Anda yakin akan menghapus data?",
                icon: "warning",
                dangerMode: true,
                buttons: true,
            })
            .then((confirm) => {
                if (confirm) {
                    $.ajax({
                        type: 'POST',
                        data: {'_method': 'DELETE'},
                        url: url,
                        dataType: 'json',
                        success: function (response) {
                            window.location.reload();
                        }
                    });
                }
            });
            
        })
    })
</script>
@endsection