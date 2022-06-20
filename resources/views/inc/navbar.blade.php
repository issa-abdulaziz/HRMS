<nav class="navbar navbar-expand-md navbar-dark bg-dark shadow-sm">
  <div class="container">
    <a href="{{ route('dashboard') }}">
    <img src="{{asset('/images/mainLogo.ico')}}" alt="logo" class="navbar-brand" width="45" height="50">
    </a>
    <a class="navbar-brand" href="{{ route('dashboard') }}">
      {{ env('APP_NAME') }}
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <!-- Left Side Of Navbar -->
      <ul class="navbar-nav mr-auto">
        <li class="nav-item">
          <a class="nav-link {{ request()->is('employee*') ? 'active' : '' }}"
            href="{{ route('employee.index') }}">Employee</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->is('attendance*') ? 'active' : '' }}"
            href="{{ route('attendance.index') }}">Attendance</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->is('overtime*') ? 'active' : '' }}"
            href="{{ route('overtime.index') }}">Overtime</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->is('vacation*') ? 'active' : '' }}"
            href="{{ route('vacation.index') }}">Vacation</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->is('advanced-payment*') ? 'active' : '' }}"
            href="{{ route('advanced-payment.index') }}">Advanced Payment</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->is('salary*') ? 'active' : '' }}"
            href="{{ route('salary.index') }}">Salary</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->is('shift*') ? 'active' : '' }}" href="{{ route('shift.index') }}">Work
            Shifts</a>
        </li>
      </ul>

      <!-- Right Side Of Navbar -->
      <ul class="navbar-nav ml-auto">
        <!-- Authentication Links -->
        @guest
          <li class="nav-item">
            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
          </li>
          @if (Route::has('register'))
            <li class="nav-item">
              <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
            </li>
          @endif
        @else
          <li class="nav-item dropdown">
            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
              aria-haspopup="true" aria-expanded="false" v-pre>
              {{ Auth::user()->name }}
            </a>

            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="{{ route('setting.index') }}">Settings</a>
               <a class="dropdown-item" href="{{ route('logout') }}" >
                {{ __('Logout') }}
              </a>

              {{-- <form id="logout-form" action="{{ route('logout') }}" method="GET" class="d-none">
                @csrf
              </form> --}}
            </div>
          </li>
        @endguest
      </ul>
    </div>
  </div>
</nav>
