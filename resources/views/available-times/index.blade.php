@extends('layouts.app')

@section('content')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Scheduler</a></li>
    <li class="breadcrumb-item active">Available Time</li>
</ol>    
<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row justify-content-center align-items-center">
            <div class="col-8">
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="card-title float-left">Available Time</h5>
                        <div class="float-right">
                            <a href="{{ route('available-times.create') }}" class="btn btn-sm btn-success"><i class="fas fa-plus"></i> Tambah</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive table-sm">
                            <table class="table table-bordered" id="tbl-available-time">
                                <thead>
                                    <tr>
                                        <th>Start Time</th>
                                        <th>End Time</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($available_times->total() > 0)
                                        @foreach ($available_times as $time)
                                            <tr data-id="{{ $time->id }}">
                                                <td class="start-time" data-value="{{ $time->start_time }}">{{ $time->start_time }}</td>
                                                <td class="end-time" data-value="{{ $time->end_time }}">{{ $time->end_time }}</td>
                                                <td class="text-center">
                                                    <a href="{{ route('available-times.edit', $time->id) }}" class="btn-link edit-time" title="Edit Data">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a> &nbsp;
                                                    <a href="{{ route('available-times.delete', $time->id) }}" class="btn-link text-danger delete-time" title="Delete Data">
                                                        <i class="fas fa-trash"></i> Hapus
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <th colspan="4" class="text-danger">No data available</th>
                                        </tr>
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
@endsection

@section('scripts')
    <script>
        $(function () {
            $('.delete-time').on('click', function (e) {
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