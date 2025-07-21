@extends('masters.layouts.master')

@section('title', '驗證你的信箱')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('驗證你的信箱') }}</div>

                    <div class="card-body">
                        @if (session('resent'))
                            <div class="alert alert-success" role="alert">
                                {{ __('新的驗證連結已發送至您的電子郵件地址.') }}
                            </div>
                        @endif

                        {{ __('在繼續操作之前，請檢查您的電子郵件以獲取驗證鏈接.') }}
                        {{ __('如果還沒有收到驗證信') }}，
                        <form class="d-inline" method="POST" action="{{ route('masters.verification.send') }}">
                            @csrf
                            <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('重新傳送') }}</button>。
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
