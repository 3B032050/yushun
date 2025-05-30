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
            <div class="d-flex justify-content-between align-items-center mt-2">
                <p class="fs-4 mb-0">
                    <a href="{{ route('users.index') }}" class="custom-link"><i class="fa fa-home"></i></a> >
                    個人資料
                </p>

                {{-- 字體大小控制按鈕 --}}
                <div class="text-size-controls">
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
                                    <div class="mb-3 text-break"><strong>LINE ID：</strong> {{ $user->line_id }}</div>
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
<<<<<<< HEAD
=======
@push('styles')
    <style>
        #content.small { font-size: 14px; }
        #content.medium { font-size: 22px; }
        #content.large { font-size: 30px; }

        .text-size-controls {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 999;
        }

        .text-size-controls button {
            margin-left: 5px;
            padding: 5px 10px;
            font-size: 14px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        function setFontSize(size) {
            const content = document.getElementById('content');
            content.classList.remove('small', 'medium', 'large');
            content.classList.add(size);
        }
    </script>
@endpush
>>>>>>> refs/remotes/origin/master
