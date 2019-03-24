<div class="sidebar">
    <nav class="sidebar-nav ps">
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link active" href="{{ url('/') }}">
                    <i class="nav-icon fas text-light fa-home"></i>
                    Dashboard
                </a>
            </li>
            @if (Auth::user()->hasRole('admin'))
            
                <li class="nav-item nav-dropdown">
                    <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon text-light fas fa-tools"></i> General Settings</a>
                    <ul class="nav-dropdown-items">
                        {{-- <li class="nav-item">
                            <a href="{{ route('roles') }}" class="nav-link"><i class="nav-icon text-light fas fa-user"></i> Role</a>
                        </li> --}}
                        <li class="nav-item">
                            <a href="{{ route('available-times') }}" class="nav-link"><i class="nav-icon text-light fas fa-clock"></i> Set Available Time</a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item nav-dropdown">
                    <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon text-light fas fa-list"></i> Master</a>
                    <ul class="nav-dropdown-items">
                        <li class="nav-item">
                            <a href="{{ route('sites') }}" class="nav-link"><i class="nav-icon text-light fas fa-arrow-right"></i> Site</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('directorates') }}" class="nav-link"><i class="nav-icon text-light fas fa-arrow-right"></i> Directorate</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('organizations') }}" class="nav-link"><i class="nav-icon text-light fas fa-arrow-right"></i> Organization</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('departments') }}" class="nav-link"><i class="nav-icon text-light fas fa-arrow-right"></i> Department</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('units') }}" class="nav-link"><i class="nav-icon text-light fas fa-arrow-right"></i> Unit</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('classes') }}" class="nav-link"><i class="nav-icon text-light fas fa-arrow-right"></i> Class</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('users') }}" class="nav-link"><i class="nav-icon text-light fas fa-arrow-right"></i> User</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('trainings') }}" class="nav-link"><i class="nav-icon text-light fas fa-arrow-right"></i> Training</a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item nav-dropdown">
                    <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon text-light fas fa-calendar-alt"></i> Training Schedule</a>
                    <ul class="nav-dropdown-items">
                        <li class="nav-item">
                            <a href="{{ route('trainings.schedules') }}" class="nav-link"><i class="nav-icon text-light fas fa-calendar-week"></i> Training Schedule</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('attendances.re-registration') }}" class="nav-link"><i class="nav-icon text-light fas fa-calendar-check"></i> Re-Registration</a>
                        </li>
                    </ul>
                </li>

            @else
                <li class="nav-item">
                    <a href="{{ route('reservations') }}" class="nav-link"><i class="nav-icon text-light fas fa-user-check"></i> My Training</a>
                </li>
            @endif
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#">
                    <i class="nav-icon text-light fas fa-file"></i>
                    Reports
                </a>
                <ul class="nav-dropdown-items">
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon text-light fas fa-file-alt"></i> Report
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
    <button class="sidebar-minimizer brand-minimizer" type="button"></button>
</div>