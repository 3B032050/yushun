@extends('masters.layouts.master')

@section('title', '禁止存取')

@section('content')
    <div class="container text-center mt-5">
        <h1 class="text-danger">403</h1>
        <p class="lead">抱歉，你無權訪問此頁面。</p>

        <div class="mt-4">
            <a href="{{ url()->previous() }}" class="btn btn-primary">返回上一頁</a>
            <a href="{{ route('login') }}" class="btn btn-secondary">重新登入</a>
        </div>
    </div>
@endsection
