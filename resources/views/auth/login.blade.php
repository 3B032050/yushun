@extends('layouts.master')

@section('content')
    <br>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('登入') }}</div>
                @if (Auth::user() && !Auth::user()->hasVerifiedEmail())
                    <div class="alert alert-warning">
                        您尚未驗證信箱，<a href="{{ route('verification.notice') }}">點我前往驗證</a>
                    </div>
                @endif
                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('信箱 / Email') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('信箱 / email')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('手機號碼 / Mobile Phone number') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" value="{{ old('password') }}" required autocomplete="password" autofocus>

                                @error('手機號碼 / Mobile Phone number')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

{{--                        <div class="row mb-3">--}}
{{--                            <div class="col-md-6 offset-md-4">--}}
{{--                                <div class="form-check">--}}
{{--                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>--}}

{{--                                    <label class="form-check-label" for="remember">--}}
{{--                                        {{ __('Remember Me') }}--}}
{{--                                    </label>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('登入') }}
                                </button>
                                <a href="{{ route('google.login') }}" class="btn btn-danger">
                                    <i class="fa-brands fa-google"></i> {{ __('使用 Google 登入') }}
                                </a>
                            </div>
                        </div>
                        @if ($errors->any())
                            <script>
                                let messages = "";
                                @foreach ($errors->all() as $error)
                                    messages += "{{ $error }}\n";
                                @endforeach
                                alert(messages);  // 以瀏覽器 alert 彈窗顯示錯誤訊息
                            </script>
                        @endif

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
