<nav class="navbar navbar-expand-lg navbar-light bg-custom">
    <div class="container-fluid px-3 px-lg-5">
        <a class="navbar-brand fw-bold fs-3" href="{{ url('/index') }}">豫順家居</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse mt-2 mt-lg-0" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 d-flex align-items-center gap-2">

                @if (Auth::guard('web')->check())
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-dark" id="navbarDropdown" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            使用者：{{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li>
                                <a class="dropdown-item text-dark" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    {{ __('登出') }}
                                </a>
                            </li>
                        </ul>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                @elseif (Auth::guard('master')->check())
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-dark" id="navbarDropdown" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            {{ Auth::guard('master')->user()->position == '0' ? '管理員' : '師傅' }}：{{ Auth::guard('master')->user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li>
                                <a class="dropdown-item text-dark" href="{{ route('masters_logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form-master').submit();">
                                    {{ __('登出') }}
                                </a>
                            </li>
                        </ul>
                        <form id="logout-form-master" action="{{ route('masters_logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-dark" id="navbarDropdown" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            師傅端
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item text-dark" href="{{ route('masters_login') }}">{{ __('師傅登入') }}</a></li>
                            <li><a class="dropdown-item text-dark" href="{{ route('masters_register') }}">{{ __('師傅註冊') }}</a></li>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link text-dark" href="{{ route('login') }}">{{ __('登入') }}</a></li>
                    <li class="nav-item"><a class="nav-link text-dark" href="{{ route('register') }}">{{ __('註冊') }}</a></li>
                @endif

            </ul>
        </div>
    </div>
</nav>

<style>
    .bg-custom {
        background-color: #EEEDEC;
    }

    .navbar {
        height: auto;
        border-bottom: 2px solid #b4b6b6;
    }

    @media (max-width: 991.98px) {
        .navbar-nav .nav-link {
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .dropdown-menu {
            width: 100%;
        }
    }
</style>
