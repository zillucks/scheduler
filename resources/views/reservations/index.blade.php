@extends('layouts.app')

@section('content')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Scheduler</a></li>
    <li class="breadcrumb-item active">Booking</li>
</ol>
<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-white">
                        <h4 class="card-title float-left">Booking</h4>
                        <div class="float-right">
                            <a href="{{ route('reservations.booking-wizard', 'step=1') }}" class="btn btn-sm btn-success"><i class="fas fa-plus"></i> Booking</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="GET" id="form-search-class" class="mb-4">
                            <div class="form-row">
                                <div class="form-group col-sm-4">
                                    <label for="full_name">Full Name</label>
                                    <input type="text" name="full_name" id="full_name" class="form-control" placeholder="Full Name" value="{{ request()->get('full_name') }}">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" id="email" class="form-control" placeholder="Email" value="{{ request()->get('email') }}">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-sm-4">
                                    <label for="site_name">Site</label>
                                    <input type="text" name="site_name" id="site_name" class="form-control" placeholder="Site Name" value="{{ request()->get('site_name') }}">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="directorate_name">Directorate</label>
                                    <input type="text" name="directorate_name" id="directorate_name" class="form-control" placeholder="Directorate" value="{{ request()->get('directorate_name') }}">
                                </div>
                            </div>
                            <div class="form-group justify-content-center">
                                <div class="col-sm-4">
                                    <button type="submit" class="btn btn-square btn-block btn-success" id="btn-filter">Cari</button>
                                </div>
                            </div>
                        </form>
                        <div class="table-responsive table-sm">
                            <table class="table table-bordered" id="tbl-class">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 20%">Fullname</th>
                                        <th class="text-center" style="width: 20%">Email</th>
                                        <th class="text-center" style="width: 14%">Site</th>
                                        <th class="text-center" style="width: 13%">Training Date</th>
                                        <th class="text-center" style="width: 13%">Booked Date</th>
                                        <th class="text-center" style="width: 20%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($reservations->total() > 0) @foreach ($reservations as $i => $reservation)
                                    <tr class="{{ $reservation->status_class }}">
                                        <td>{{ $reservation->identity->full_name }}</td>
                                        <td>{{ $reservation->identity->email }}</td>
                                        <td>{{ $reservation->identity->site->site_name }}</td>
                                        <td>{{ $reservation->reservation_date }}</td>
                                        <td>{{ \Carbon\Carbon::parse($reservation->created_at)->format('Y-m-d') }}</td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <a href="{{ route('reservations.view', $reservation->id) }}" class="btn-link text-warning" title="Edit Data"><i class="fas fa-eye"></i> View</a>&nbsp;&nbsp;
                                                @if ($reservation->canModify())
                                                    <a href="{{ route('reservations.reschedule', $reservation->id) }}" class="btn-link text-info" title="Re-schedule"><i class="fas fa-home"></i> Re-schedule</a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach @else
                                    <tr>
                                        <th colspan="6" class="text-danger"><em>No data available</em></th>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                            @if ($reservations->total() > 0) {!! $reservations->links() !!} @endif
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