@extends('masters.layouts.master')

@section('title', '豫順家居媒合服務平台')

@section('content')
    <div class="content-wrapper">
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center mt-2 flex-wrap">
                <nav aria-label="breadcrumb" class="mb-2 mb-md-0 w-100 w-md-auto">
                    <ol class="breadcrumb breadcrumb-path">
                        <li class="breadcrumb-item"><a href="{{ route('masters.index') }}"><i class="fa fa-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admins.masters.index') }}">師傅資料管理</a></li>
                        <li class="breadcrumb-item active" aria-current="page"> 新增師傅資料</li>
                    </ol>
                </nav>
                <div class="btn-group btn-group-sm text-size-controls mt-2" role="group" aria-label="字級調整">
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('small')">小</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('medium')">中</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('large')">大</button>
                </div>
            </div>
        </div>

        <div id="content" class="font-medium">
            <div class="row justify-content-center">
                <div class="col-12 col-md-10 col-lg-8">
                    <div class="card">
                        <div class="card-header text-center">{{ __('新增師傅資料') }}</div>

                        <div class="card-body">
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('admins.masters.store') }}" enctype="multipart/form-data">
                                @csrf
                                @method('POST')

                                <!-- 名稱 -->
                                <div class="row mb-3">
                                    <label for="storage_location" class="col-md-4 col-form-label text-md-end">{{ __('名稱') }}</label>
                                    <div class="col-md-6">
                                        <input id="name" type="text"
                                               class="form-control @error('name') is-invalid @enderror text-content"
                                               name="name" value="{{ old('name') }}" required autocomplete="name"
                                               placeholder="請輸入師傅名稱" autofocus>
                                        @error('name')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="row mb-3">
                                    <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email') }}</label>
                                    <div class="col-md-6">
                                        <input id="email" type="email"
                                               class="form-control @error('email') is-invalid @enderror text-content"
                                               name="email" value="{{ old('email') }}" required
                                               placeholder="請輸入師傅Email">
                                        @error('email')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- 電話 -->
                                <div class="row mb-3">
                                    <label for="phone" class="col-md-4 col-form-label text-md-end">{{ __('電話') }}</label>
                                    <div class="col-md-6">
                                        <input id="phone" type="text"
                                               class="form-control @error('phone') is-invalid @enderror text-content"
                                               name="phone" value="{{ old('phone') }}" required
                                               placeholder="請輸入師傅電話">
                                        @error('phone')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- 儲存按鈕 -->
                                <div class="row mb-0">
                                    <div class="col-md-8 offset-md-4">
                                        <button type="submit" class="btn btn-primary">{{ __('儲存') }}</button>
                                        <button type="button" class="btn btn-secondary" onclick="history.back();">{{ __('返回') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
