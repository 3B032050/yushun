@extends('masters.layouts.master')

@section('content')
    <div class="container text-center mt-5">
        <h1 class="text-danger">{{ $code ?? '錯誤' }}</h1>
        <p class="lead">{{ $message ?? '系統發生錯誤，請稍後再試。' }}</p>

        <div class="mt-4">
            <a href="{{ url()->previous() }}" class="btn btn-primary">返回上一頁</a>
            <a href="{{ route('login') }}" class="btn btn-secondary">重新登入</a>
        </div>
    </div>
@endsection
