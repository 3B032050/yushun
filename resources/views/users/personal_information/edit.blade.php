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
                        <li class="breadcrumb-item"><a href="{{ route('users.schedule.index') }}"> 預約時段</a></li>
                        <li class="breadcrumb-item active" aria-current="page"> 編輯個人資料</li>
                    </ol>
                </nav>

                <div class="text-size-controls btn-group btn-group-sm" role="group" aria-label="字級調整">
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('small')">小</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('medium')">中</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('large')">大</button>
                </div>
            </div>
        </div>
            <div id="content" class="medium">
                <section id="location" class="text-content"><br>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-12 col-md-10 col-lg-8">
                            <div class="card">
                                <div class="card-header">{{ __('編輯個人資料') }}</div>

                                <div class="card-body">
                                    <form method="POST" action="{{ route('users.personal_information.update', ['hash_user' => \Vinkla\Hashids\Facades\Hashids::encode($user->id)]) }}">
                                        @csrf
                                        @method('PATCH')

                                        <div class="row mb-3">
                                            <label for="name" class="col-md-4 col-form-label text-md-end">姓名：</label>
                                            <div class="col-md-6">
                                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                                       name="name" value="{{ old('name', $user->name) }}" required>
                                                @error('name')
                                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="email" class="col-md-4 col-form-label text-md-end">信箱：</label>
                                            <div class="col-md-6">
                                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                                       name="email" value="{{ old('email', $user->email) }}" required>
                                                @error('email')
                                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="mobile" class="col-md-4 col-form-label text-md-end">手機號碼：</label>
                                            <div class="col-md-6">
                                                <input id="mobile" type="text" class="form-control @error('mobile') is-invalid @enderror"
                                                       name="mobile" value="{{ old('mobile', $user->mobile) }}" required>
                                                @error('mobile')
                                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="phone" class="col-md-4 col-form-label text-md-end">市話：</label>
                                            <div class="col-md-6">
                                                <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror"
                                                       name="phone" value="{{ old('phone', $user->phone) }}">
                                                @error('phone')
                                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="address" class="col-md-4 col-form-label text-md-end">地址：</label>
                                            <div class="col-md-6">
                                                <input id="address" type="text" class="form-control @error('address') is-invalid @enderror"
                                                       name="address" value="{{ old('address', $user->address) }}" required>
                                                @error('address')
                                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-0">
                                            <div class="col-md-8 offset-md-4">
                                                <button type="submit" class="btn btn-success">儲存</button>
                                                <a href="{{ route('users.personal_information.personal_index') }}" class="btn btn-secondary">取消</a>
                                            </div>
                                        </div>
                                    </form>
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
