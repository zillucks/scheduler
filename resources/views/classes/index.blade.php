@extends('layouts.app')

@section('content')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Scheduler</a></li>
    <li class="breadcrumb-item active">Class</li>
</ol>
<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-white">
                        <h4 class="card-title float-left">Class</h4>
                        <div class="float-right">
                            <a href="{{ route('classes.create') }}" class="btn btn-sm btn-success"><i class="fas fa-plus"></i> Tambah</a>
                        </div>
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
                                            <th class="text-center" style="width: 10%">Class ID</th>
                                            <th class="text-center" style="width: 35%">Nama Class</th>
                                            <th class="text-center" style="width: 20%">Site</th>
                                            <th class="text-center" style="width: 10%">Max Student</th>
                                            <th class="text-center" style="width: 10%">Status</th>
                                            <th class="text-center" style="width: 15%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($classes->total() > 0) @foreach ($classes as $i => $class)
                                        <tr>
                                            <td>{{ $class->slug }}</td>
                                            <td>{{ $class->class_name }}</td>
                                            <td>{{ $class->site->site_name }}</td>
                                            <td>{{ $class->max_quotes }}</td>
                                            <td>{!! $class->status !!}</td>
                                            <td class="text-center">
                                                <a href="{{ route('classes.edit', $class->slug) }}" class="btn-link" title="Edit Data">
                                                                                                        <i class="fas fa-edit"></i> Edit
                                                                                                    </a>
                                                                                                    &nbsp;
                                                <a href="{{ route('classes.delete', $class->slug) }}" class="btn-link text-danger delete-class" title="Delete Data">
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
                                @if ($classes->total() > 0) {!! $classes->links() !!} @endif
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
        var tbl = $('#tbl-class');
        
        tbl.find('.delete-class').on('click', function (e) {
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