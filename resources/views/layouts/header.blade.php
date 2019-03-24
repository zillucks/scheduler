<header class="app-header navbar navbar-dark bg-red">
    <button class="navbar-toggler sidebar-toggler d-lg-none mr-auto" type="button" data-toggle="sidebar-show">
        <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand" href="{{ url('/') }}">
        {{-- <i class="fas fa-lg fa-calendar-day"></i> --}}
        <strong>Scheduler v.1.0</strong>
        {{-- <img class="navbar-brand-full" src="svg/modulr.svg" width="89" height="25" alt="i-Tor"> --}}
        {{-- <img class="navbar-brand-minimized" src="svg/modulr-icon.svg" width="30" height="30" alt="Modulr Logo"> --}}
    </a>
    <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button" data-toggle="sidebar-lg-show">
        <i class="fas fa-fw fa-bars text-white"></i>
    </button>
    <ul class="nav navbar-nav ml-auto mr-3">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-2x fa-user-circle text-success"></i>
                {{-- <img class="img-avatar mx-1" src="" alt="Avatar Image"> --}}
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow mt-2">
                <a class="dropdown-item">
                    @if (Auth::check())
                        {!! Auth::user()->username !!}
                    @endif
                    <br>
                    <small class="text-muted">User email</small>
                </a>
                <a class="dropdown-item" href="/profile">
                    <i class="fas fa-user"></i> Profile
                </a>
                <div class="divider"></div>
                <a class="dropdown-item" href="{{ route('logout') }}">
                    <i class="fas fa-sign-out-alt"></i> {{ __('Logout') }}
                </a>
            </div>
        </li>
    </ul>
</header>