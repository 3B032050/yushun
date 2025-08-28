@extends('masters.layouts.master')

@section('title', '頁面不存在')

@section('content')
    <div class="content-wrapper d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="text-center">
            <h1 class="display-1 text-danger">404</h1>
            <h3 class="mb-4">抱歉，您訪問的頁面不存在。</h3>
            <p>請確認網址是否正確或返回首頁。</p>
            <a href="{{ route('login') }}" class="btn btn-secondary">重新登入</a>
        </div>
    </div>
@endsection

<style>
    .content-wrapper {
        background-color: #f8f9fa;
    }
    h1.display-1 {
        font-size: 8rem;
        font-weight: bold;
    }
</style>
