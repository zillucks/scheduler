@extends('layouts.app')

@section('content')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Scheduler</a></li>
    <li class="breadcrumb-item active">Dashboard</li>
</ol>
<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Dashboard</h4>
                        @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                        @endif You are logged in!
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
