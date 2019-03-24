@extends('layouts.app')

@section('content')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Scheduler</a></li>
    <li class="breadcrumb-item active">User</li>
</ol>
<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row justify-content-center">
            <div class="col-12">
                {{-- @include('layouts.session') --}}
                <div class="card">
                    <div class="card-header bg-white">
                        <h4 class="card-title float-left">User</h4>
                        <div class="float-right">
                            <a href="{{ route('users.import') }}" class="btn btn-sm btn-dark"><i class="fas fa-file-excel"></i> Import Users</a>
                            <a href="{{ route('users.create') }}" class="btn btn-sm btn-success"><i class="fas fa-plus"></i> Tambah</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="GET" id="form-search-class">
                            <div class="form-group row">
                                <label for="search" class="col-sm-3 col-md-2 col-form-label">Search</label>
                                <div class="col-6">
                                    <input type="text" name="search" id="search" class="form-control" placeholder="Cari User" value="{{ request()->get('search') }}">
                                </div>
                            </div>
                        </form>
                        <div class="row">
                            <div class="table-responsive table-sm">
                                <table class="table table-bordered" id="tbl-class">
                                    <thead>
                                        <tr>
                                            <th class="text-left" style="width: 10%">Username</th>
                                            <th class="text-left" style="width: 15%">Full Name</th>
                                            <th class="text-left" style="width: 10%">Email</th>
                                            <th class="text-left" style="width: 9%">Site</th>
                                            <th class="text-left" style="width: 10%">Directorate</th>
                                            <th class="text-left" style="width: 10%">Organization</th>
                                            <th class="text-left" style="width: 10%">Department</th>
                                            <th class="text-left" style="width: 10%">Unit</th>
                                            <th class="text-center" style="width: 8%">Status</th>
                                            <th class="text-center" style="width: 8%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($users->total() > 0) @foreach ($users as $i => $user)
                                        <tr>
                                            <td>{{ $user->username }}</td>
                                            <td>{{ isset($user->identity) ? $user->identity->full_name : '' }}</td>
                                            <td>{{ isset($user->identity) ? $user->identity->email : '' }}</td>
                                            <td>{{ isset($user->identity->site) ? $user->identity->site->site_name : '' }}</td>
                                            <td>{{ isset($user->identity->directorate) ? $user->identity->directorate->directorate_name : '' }}</td>
                                            <td>{{ isset($user->identity->organization) ? $user->identity->organization->organization_name : '' }}</td>
                                            <td>{{ isset($user->identity->department) ? $user->identity->department->department_name : '' }}</td>
                                            <td>{{ isset($user->identity->unit) ? $user->identity->unit->unit_name : '' }}</td>
                                            <td>{!! isset($user->identity) ? $user->identity->status : '' !!}</td>
                                            <td class="text-center">
                                                @if (Auth::user()->isAdmin())
                                                    <a href="{{ route('users.setting', $user->username) }}" class="btn-link text-dark" title="Setting"><i class="fas fa-user-cog"></i></a>&nbsp;
                                                @endif
                                                <a href="{{ route('users.edit', $user->username) }}" class="btn-link" title="Edit Data"><i class="fas fa-edit"></i></a>&nbsp;
                                                <a href="{{ route('users.delete', $user->username) }}" class="btn-link text-danger delete-class" title="Delete Data"><i class="fas fa-trash"></i></a>
                                            </td>
                                        </tr>
                                        @endforeach @else
                                        <tr>
                                            <th colspan="10" class="text-danger"><em>No data available</em></th>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                                @if ($users->total() > 0) {!! $users->links() !!} @endif
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