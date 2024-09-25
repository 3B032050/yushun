<nav class="navbar navbar-expand-lg navbar-light bg-custom">
    <div class="container px-4 px-lg-5">
        <a class="navbar-brand" href="{{ url('/') }}">
            <p style="font-size: 35px; font-weight: bold;">豫順清潔</p>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="row justify-content-center">
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav">
                    @guest('master')
                        <li class="nav-item dropdown">
                            <div class="dropdown">
                                <button class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color:black">
                                    <span class="d-none d-md-inline">師傅端</span>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li>
                                        <a class="nav-link" href="{{ route('masters_login') }}" style="color:black">{{ __('師傅登入') }}</a>
                                    </li>
                                    <li>
                                        <a class="nav-link" href="{{ route('masters_register') }}" style="color:black">{{ __('師傅註冊') }}</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color:black">師傅：{{ Auth::guard('master')->user()->name }}</a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li>
                                    <a class="dropdown-item" href="{{ route('masters_logout') }}"
                                       onclick="event.preventDefault();
                       document.getElementById('logout-form-master').submit();" style="color:black">{{ __('登出') }}</a>
                                </li>
                                <form id="logout-form-master" action="{{ route('masters_logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </ul>
                        </li>
                    @endguest
                </ul>
                <ul class="navbar-nav">
                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}" style="color:black">{{ __('登入') }}</a>
                            </li>
                        @endif

                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}" style="color:black">{{ __('註冊') }}</a>
                            </li>
                        @endif
                    @else
                        <ul class="nav-item dropdown">
                            <li>
                                @if (Auth::check())
                                    <a class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color:black">使用者：{{ Auth::user()->name }}</a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('logout') }}"
                                               onclick="event.preventDefault();
                           document.getElementById('logout-form').submit();" style="color:black">{{ __('登出') }}</a>
                                        </li>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </ul>
                                @endif
                            </li>
                        </ul>
                    @endguest
                </ul>
            </div>
        </div>
    </div>
</nav>
<style>
    .bg-custom {
        background-color: #EEEDEC;
    }
    .navbar {
        height: 80px;
        border-bottom: 2px solid #b4b6b6;
    }
</style>

