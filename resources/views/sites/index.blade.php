@extends('layouts.app')

@section('content')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Scheduler</a></li>
    <li class="breadcrumb-item active">Site</li>
</ol>
<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-white">
                        <h4 class="card-title float-left">Site</h4>
                        <div class="float-right">
                            <a href="{{ route('sites.import') }}" class="btn btn-sm btn-dark"><i class="fas fa-file-excel"></i> Import Site</a>
                            <a href="{{ route('sites.create') }}" class="btn btn-sm btn-success"><i class="fas fa-plus"></i> Tambah</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="GET" id="form-search-site">
                            <div class="form-group row">
                                <label for="site_name" class="col-sm-3 col-md-2 col-form-label">Search</label>
                                <div class="col-6">
                                    <input type="text" name="site_name" id="site_name" class="form-control" placeholder="Cari Site" value="{{ request()->get('site_name') }}">
                                </div>
                            </div>
                        </form>
                        <div class="row">
                            <div class="table-responsive table-sm">
                                <table class="table table-bordered" id="tbl-site">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 10%">Site ID</th>
                                            <th class="text-center" style="width: 60%">Nama Site</th>
                                            <th class="text-center" style="width: 15%">Status</th>
                                            <th class="text-center" style="width: 15%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($sites->total() > 0) @foreach ($sites as $i => $site)
                                        <tr>
                                            <td>{{ $site->slug }}</td>
                                            <td>{{ $site->site_name }}</td>
                                            <td>{!! $site->status !!}</td>
                                            <td class="text-center">
                                                <a href="{{ route('sites.edit', $site->slug) }}" class="btn-link" title="Edit Data"><i class="fas fa-edit"></i> Edit</a>&nbsp;
                                                <a href="{{ route('sites.delete', $site->slug) }}" class="btn-link text-danger delete-site" title="Delete Data"><i class="fas fa-trash"></i> Hapus</a>
                                            </td>
                                        </tr>
                                        @endforeach @else
                                        <tr>
                                            <th colspan="4" class="text-danger"><em>No data available</em></th>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                                @if ($sites->total() > 0) {!! $sites->links() !!} @endif
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
        var tbl = $('#tbl-site');
        
        tbl.find('.delete-site').on('click', function (e) {
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