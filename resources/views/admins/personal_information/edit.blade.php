@extends('masters.layouts.master')

@section('title','豫順家居服務媒合平台')
@section('content')
    <div class="content-wrapper">
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center mt-2">
                <nav aria-label="breadcrumb" class="mb-2 mb-md-0 w-100 w-md-auto">
                    <ol class="breadcrumb breadcrumb-path mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('masters.index') }}"><i class="fa fa-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admins.personal_information.index') }}"> 個人資料</a></li>
                        <li class="breadcrumb-item active" aria-current="page"> 編輯個人資料</li>
                    </ol>
                </nav>

                <div class="text-size-controls btn-group btn-group-sm" role="group" aria-label="字程調整">
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('small')">小</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('medium')">中</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('large')">大</button>
                </div>
            </div>
        </div>
        <div id="content" class="medium">
            <div class="row justify-content-center mt-3">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header text-center">{{ __('編輯個人資料') }}</div>

                        <div class="card-body">
                            <form method="POST" action="{{ route('admins.personal_information.update', ['hashedMasterId' => $hashedMasterId]) }}">
                                @csrf
                                @method('PATCH')

                                <div class="row mb-3">
                                    <label for="name" class="col-md-4 col-form-label text-md-end"><span class="required"></span>{{ __('姓名') }}</label>
                                    <div class="col-md-6">
                                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $master->name }}" required placeholder="必填" autofocus>
                                        @error('name')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="email" class="col-md-4 col-form-label text-md-end"><span class="required"></span>{{ __('信箱') }}</label>
                                    <div class="col-md-6">
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $master->email }}" required placeholder="必填">
                                        @error('email')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="phone" class="col-md-4 col-form-label text-md-end"><span class="required"></span>{{ __('電話') }}</label>
                                    <div class="col-md-6">
                                        <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ $master->phone }}" required placeholder="必填">
                                        @error('phone')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-0">
                                    <div class="col-md-8 offset-md-4">
                                        <button type="submit" class="btn btn-primary">{{ __('儲存') }}</button>
                                        <a href="javascript:history.back()" class="btn btn-secondary ml-2">{{ __('取消') }}</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<style>
    a.disabled, .no-click {
        pointer-events: none;  /* 不能點 */
        opacity: .65;          /* 視覺灰掉 */
        cursor: not-allowed;
    }
</style>
