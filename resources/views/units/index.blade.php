@extends('layouts.app')

@section('content')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Scheduler</a></li>
    <li class="breadcrumb-item active">Unit</li>
</ol>
<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-white">
                        <h4 class="card-title float-left">Unit</h4>
                        <div class="float-right">
                            <a href="{{ route('units.import') }}" class="btn btn-sm btn-dark"><i class="fas fa-file-excel"></i> Import Unit</a>
                            <a href="{{ route('units.create') }}" class="btn btn-sm btn-success"><i class="fas fa-plus"></i> Tambah</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="GET" id="form-search-unit">
                            <div class="form-group row">
                                <label for="unit_name" class="col-sm-3 col-md-2 col-form-label">Search</label>
                                <div class="col-6">
                                    <input type="text" name="unit_name" id="unit_name" class="form-control" placeholder="Cari Unit" value="{{ request()->get('unit_name') }}">
                                </div>
                            </div>
                        </form>
                        <div class="row">
                            <div class="table-responsive table-sm">
                                <table class="table table-bordered" id="tbl-unit">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 10%">Unit ID</th>
                                            <th class="text-center" style="width: 60%">Nama Unit</th>
                                            <th class="text-center" style="width: 15%">Status</th>
                                            <th class="text-center" style="width: 15%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($units->total() > 0) @foreach ($units as $i => $unit)
                                        <tr>
                                            <td>{{ $unit->slug }}</td>
                                            <td>{{ $unit->unit_name }}</td>
                                            <td>{!! $unit->status !!}</td>
                                            <td class="text-center">
                                                <a href="{{ route('units.edit', $unit->slug) }}" class="btn-link" title="Edit Data">
                                                                                                        <i class="fas fa-edit"></i> Edit
                                                                                                    </a>
                                                                                                    &nbsp;
                                                <a href="{{ route('units.delete', $unit->slug) }}" class="btn-link text-danger delete-unit" title="Delete Data">
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
                                @if ($units->total() > 0) {!! $units->links() !!} @endif
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
        var tbl = $('#tbl-unit');
        
        tbl.find('.delete-unit').on('click', function (e) {
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