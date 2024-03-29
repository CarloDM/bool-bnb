<aside class="my_aside shadow">

    {{-- LOGO --}}
    {{-- <div class="logo ps-3">
        <a href="{{ url('/') }}">
            <i class="fa-solid fa-house d-md-none my-4 "></i>
            <img class=" d-none d-md-block" src="{{ asset('img/boolbnb-sfondo-trasparente.png') }}" alt="logo">
        </a>
    </div> --}}

    {{-- MAIN LINK --}}
    <div class="links h-75 pt-5">

        <ul class="navbar-nav">
            <li class="nav-item {{ Route::currentRouteName() === 'dashboard' ? 'active' : '' }}" title="Dashboard home">
                <a class="nav-link " href="{{ route('dashboard') }}">
                    <span class="d-none d-md-block">Dashboard home</span>
                    <i class="fa-solid fa-chart-line d-md-none"></i>
                </a>
            </li>

            @if (Auth::user()->apartments->count())
                <li class="nav-item {{ Route::currentRouteName() === 'admin.apartments.index' ? 'active' : '' }}" title="I miei appartamenti">
                    <a class="nav-link" href="{{ route('admin.apartments.index') }}">
                        <span class="d-none d-md-block">I miei appartamenti</span>
                        <i class="fa-solid fa-house-user d-md-none"></i>
                    </a>
                </li>
            @endif

            <li class="nav-item {{ Route::currentRouteName() === 'admin.apartments.create' ? 'active' : '' }}" title="Aggiungi">
                <a class="nav-link" href="{{ route('admin.apartments.create') }}">
                    <span class="d-none d-md-block">Aggiungi appartamento</span>
                    <i class="fa-regular fa-square-plus d-md-none"></i>
                </a>
            </li>

            @if (Auth::user()->apartments->count())
            <li class="nav-item {{ Route::currentRouteName() === '#' ? 'active' : '' }}" title="Inbox">
                <a class="nav-link" href="{{ route('messages.getMessages')}}">
                    <span class="d-none d-md-block">Inbox</span>
                    <i class="fa-regular fa-envelope d-md-none"></i>
                </a>
            </li>
            @endif

            @if (Auth::user()->apartments->count())
            <li class="nav-item {{ Route::currentRouteName() === 'admin.apartments.service-apartments' ? 'active' : '' }}" title="Sponsorship">
                <a class="nav-link" href="{{ route('sponsorship') }}">
                    <span class="d-none d-md-block">Sponsorships</span>
                    <i class="fa-solid fa-rectangle-ad d-md-none"></i>
                </a>
            </li>
            @endif
        </ul>
    </div>

    {{-- LOG-OUT BUTTON --}}
    <div class="log-out-button text-center">
        <button type="submit" class="btn btn-logout">

            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
            document.getElementById('logout-form').submit();">
                <span class="d-none d-md-block">Torna al sito pubblico</span>
                <i class="fa-solid fa-house-chimney d-md-none"></i>
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>

        </button>
    </div>
</aside>
