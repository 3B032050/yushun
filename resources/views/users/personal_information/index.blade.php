@extends('users.layouts.master')

@section('title','個人資料')

@section('content')
    <div class="content-wrapper">
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="container-fluid px-4">
            <div style="margin-top: 10px;">
                <p style="font-size: 1.8em;">
                    <a href="{{ route('users.index') }}" class="custom-link"><i class="fa fa-home"></i></a> >
                    個人資料
                </p>
            </div>
        </div>

        <section id="location"><br>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">{{ __('個人資料') }}</div>

                            <div class="card-body">
                                <div class="mb-3"><strong>姓名：</strong> {{ $user->name }}</div>
                                <div class="mb-3"><strong>信箱：</strong> {{ $user->email }}</div>
                                <div class="mb-3"><strong>手機號碼：</strong> {{ $user->mobile }}</div>
                                <div class="mb-3"><strong>市話：</strong> {{ $user->phone }}</div>
                                <div class="mb-3"><strong>地址：</strong> {{ $user->address }}</div>
                                <div class="mb-3"><strong>LINE ID：</strong> {{ $user->line_id }}</div>

                                <div class="mt-4">
                                    <a href="{{ route('users.personal_information.edit', ['hash_user' => \Vinkla\Hashids\Facades\Hashids::encode($user->id)]) }}" class="btn btn-primary">
                                        編輯
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
