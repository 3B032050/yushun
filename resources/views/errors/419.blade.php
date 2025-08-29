@extends('masters.layouts.master')

@section('content')
    <div class="container text-center mt-5">
        <h1 class="text-warning">登入逾時</h1>
        <p class="lead">您的登入已逾時，請重新登入。</p>

        <div class="mt-4">
            <a href="{{ route('logout.then.login') }}" class="btn btn-secondary">重新登入</a>

        </div>
    </div>
@endsection
