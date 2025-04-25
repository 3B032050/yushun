<nav class="navbar navbar-expand-lg navbar-light bg-custom border-bottom">
    <div class="container px-4 px-lg-5">
        <a class="navbar-brand fw-bold fs-3" href="{{ url('/index') }}">豫順清潔</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse mt-2 mt-lg-0" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                @if (Auth::guard('web')->check())
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-dark" href="#" id="navbarDropdown" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            使用者：{{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-dark">
                                        {{ __('登出') }}
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>

                @elseif (Auth::guard('master')->check())
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-dark" href="#" id="navbarDropdown" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            @if(Auth::guard('master')->user()->position == '0')
                                管理員：{{ Auth::guard('master')->user()->name }}
                            @else
                                師傅：{{ Auth::guard('master')->user()->name }}
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li>
                                <form id="logout-form-master" action="{{ route('masters_logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-dark">
                                        {{ __('登出') }}
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>

                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-dark" href="#" id="navbarDropdown" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            師傅端
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item text-dark" href="{{ route('masters_login') }}">師傅登入</a></li>
                            <li><a class="dropdown-item text-dark" href="{{ route('masters_register') }}">師傅註冊</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="{{ route('login') }}">登入</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="{{ route('register') }}">註冊</a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>

<style>
    .bg-custom {
        background-color: #EEEDEC;
    }
</style>
