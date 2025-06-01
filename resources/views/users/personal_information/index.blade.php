@extends('users.layouts.master')

@section('title','豫順家居媒合服務平台')

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
            <div class="d-flex justify-content-between align-items-center mt-2">
                <nav aria-label="breadcrumb" class="mb-2 mb-md-0 w-100 w-md-auto">
                    <ol class="breadcrumb breadcrumb-path mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('users.index') }}"><i class="fa fa-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('users.personal_information.personal_index') }}"> 個人資料</a></li>
                    </ol>
                </nav>

                {{-- 字體大小控制按鈕 --}}
                <div class="text-size-controls btn-group btn-group-sm" role="group" aria-label="字級調整">
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('small')">小</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('medium')">中</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('large')">大</button>
                </div>
            </div>
        </div>

        <div id="content" class="medium">
            <section id="location" class="mt-4">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-12 col-md-10 col-lg-8">
                            <div class="card">
                                <div class="card-header text-center">{{ __('個人資料') }}</div>

                                <div class="card-body px-3 px-md-4">
                                    <div class="mb-3 text-break"><strong>姓名：</strong> {{ $user->name }}</div>
                                    <div class="mb-3 text-break"><strong>信箱：</strong> {{ $user->email }}</div>
                                    <div class="mb-3 text-break"><strong>手機號碼：</strong> {{ $user->mobile }}</div>
                                    <div class="mb-3 text-break"><strong>市話：</strong> {{ $user->phone }}</div>
                                    <div class="mb-3 text-break"><strong>地址：</strong> {{ $user->address }}</div>
                                    <div class="mt-4 text-center">
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
    </div>
@endsection
<style>
    .breadcrumb-path {
        font-size: 1.4em;
        white-space: normal;
        word-break: break-word;
    }

    @media (max-width: 768px) {
        .breadcrumb-path {
            font-size: 1.3em;
        }
        .text-size-controls {
            margin-top: 0.5rem;
        }
    }

    @media (max-width: 480px) {
        .breadcrumb-path {
            font-size: 1.1em;
        }
        .d-flex.flex-column.flex-md-row > .btn-group {
            width: 100%;
            justify-content: center;
        }
    }
</style>
