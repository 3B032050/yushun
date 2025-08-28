@extends('masters.layouts.master')

@section('content')
    <div class="container text-center mt-5">
        <h1 class="text-warning">登入逾時</h1>
        <p class="lead">您的登入已逾時，請重新登入。</p>

        <div class="mt-4">
            <a href="{{ route('login') }}" class="btn btn-primary">前往登入</a>
        </div>
    </div>
@endsection
