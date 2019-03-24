@extends('layouts.app')

@section('content')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Scheduler</a></li>
    <li class="breadcrumb-item active">Directorate</li>
</ol>
<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-white">
                        <h4 class="card-title float-left">Directorate</h4>
                        <div class="float-right">
                            <a href="{{ route('directorates.create') }}" class="btn btn-sm btn-success"><i class="fas fa-plus"></i> Tambah</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="GET" id="form-search-directorate">
                            <div class="form-group row">
                                <label for="directorate_name" class="col-sm-3 col-md-2 col-form-label">Search</label>
                                <div class="col-6">
                                    <input type="text" name="directorate_name" id="directorate_name" class="form-control" placeholder="Cari Directorate" value="{{ request()->get('directorate_name') }}">
                                </div>
                            </div>
                        </form>
                        <div class="row">
                            <div class="table-responsive table-sm">
                                <table class="table table-bordered" id="tbl-directorate">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 15%">Directorate ID</th>
                                            <th class="text-center" style="width: 55%">Nama Directorate</th>
                                            <th class="text-center" style="width: 15%">Status</th>
                                            <th class="text-center" style="width: 15%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($directorates->total() > 0) @foreach ($directorates as $i => $directorate)
                                        <tr>
                                            <td>{{ $directorate->slug }}</td>
                                            <td>{{ $directorate->directorate_name }}</td>
                                            <td>{!! $directorate->status !!}</td>
                                            <td class="text-center">
                                                <a href="{{ route('directorates.edit', $directorate->slug) }}" class="btn-link" title="Edit Data">
                                                                                                        <i class="fas fa-edit"></i> Edit
                                                                                                    </a>
                                                                                                    &nbsp;
                                                <a href="{{ route('directorates.delete', $directorate->slug) }}" class="btn-link text-danger delete-directorate" title="Delete Data">
                                                                                                        <i class="fas fa-trash"></i> Hapus
                                                                                                    </a>
                                            </td>
                                        </tr>
                                        @endforeach @else
                                        <tr>
                                            <th colspan="4" class="text-danger"><em>No data available</em></th>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                                @if ($directorates->total() > 0) {!! $directorates->links() !!} @endif
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
        var tbl = $('#tbl-directorate');
        
        tbl.find('.delete-directorate').on('click', function (e) {
            e.preventDefault();
            var url = $(this).attr('href');
            var rows = $(this).closest('tr');

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