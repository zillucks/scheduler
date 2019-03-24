@extends('layouts.app')

@section('content')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Scheduler</a></li>
    <li class="breadcrumb-item active">Organization</li>
</ol>
<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-white">
                        <h4 class="card-title float-left">Organization</h4>
                        <div class="float-right">
                            <a href="{{ route('organizations.import') }}" class="btn btn-sm btn-dark"><i class="fas fa-file-excel"></i> Import Organization</a>
                            <a href="{{ route('organizations.create') }}" class="btn btn-sm btn-success"><i class="fas fa-plus"></i> Tambah</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="GET" id="form-search-organization">
                            <div class="form-group row">
                                <label for="organization_name" class="col-sm-3 col-md-2 col-form-label">Search</label>
                                <div class="col-6">
                                    <input type="text" name="organization_name" id="organization_name" class="form-control" placeholder="Cari Organization" value="{{ request()->get('organization_name') }}">
                                </div>
                            </div>
                        </form>
                        <div class="row">
                            <div class="table-responsive table-sm">
                                <table class="table table-bordered" id="tbl-organization">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 15%">Organization ID</th>
                                            <th class="text-center" style="width: 55%">Nama Organization</th>
                                            <th class="text-center" style="width: 15%">Status</th>
                                            <th class="text-center" style="width: 15%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($organizations->total() > 0) @foreach ($organizations as $i => $organization)
                                        <tr>
                                            <td>{{ $organization->slug }}</td>
                                            <td>{{ $organization->organization_name }}</td>
                                            <td>{!! $organization->status !!}</td>
                                            <td class="text-center">
                                                <a href="{{ route('organizations.edit', $organization->slug) }}" class="btn-link" title="Edit Data">
                                                                                                        <i class="fas fa-edit"></i> Edit
                                                                                                    </a>
                                                                                                    &nbsp;
                                                <a href="{{ route('organizations.delete', $organization->slug) }}" class="btn-link text-danger delete-organization" title="Delete Data">
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
                                @if ($organizations->total() > 0) {!! $organizations->links() !!} @endif
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
        var tbl = $('#tbl-organization');
        
        tbl.find('.delete-organization').on('click', function (e) {
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