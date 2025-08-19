@extends('masters.layouts.master')

@section('title', '豫順家居媒合服務平台')

@section('content')
    <div class="content-wrapper">
        <div class="container-fluid px-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-2 gap-2">
                <nav aria-label="breadcrumb" class="mb-2 mb-md-0 w-100 w-md-auto">
                    <ol class="breadcrumb breadcrumb-path mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('masters.index') }}"><i class="fa fa-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admins.service_areas.index') }}">服務地區管理</a></li>
                        <li class="breadcrumb-item active" aria-current="page">新增服務地區</li>
                    </ol>
                </nav>
                <div class="text-size-controls btn-group btn-group-sm" role="group" aria-label="字級調整">
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
                        <div class="card-header text-center">{{ __('新增服務地區') }}</div>

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

                            <form method="POST" action="{{ route('admins.service_areas.store') }}" enctype="multipart/form-data">
                                @csrf
                                @method('POST')

                                <!-- 縣市名稱 -->
                                <div class="row mb-3">
                                    <label for="major_area" class="col-md-4 col-form-label text-md-end">{{ __('縣市') }}</label>
                                    <div class="col-md-6">
                                        <input id="major_area" type="text" class="form-control @error('major_area') is-invalid @enderror"
                                               name="major_area" value="{{ old('major_area') }}" required autocomplete="major_area"
                                               placeholder="請輸入縣市" autofocus>
                                        @error('major_area')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- 鄉鎮 -->
                                <div class="row mb-3">
                                    <label for="storage_location" class="col-md-4 col-form-label text-md-end">{{ __('鄉鎮') }}</label>
                                    <div class="col-md-6">
                                        <input id="minor_area" type="number" class="form-control @error('minor_area') is-invalid @enderror"
                                               name="minor_area" value="{{ old('minor_area') }}" required placeholder="請輸鄉鎮地區">
                                        @error('minor_area')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- 區域類別 -->
                                <div class="row mb-3">
                                    <label class="col-md-4 col-form-label text-md-end">{{ __('區域類別') }}</label>
                                    <div class="col-md-6 d-flex align-items-center gap-3">
                                        <div class="form-check">
                                            <input type="radio" class="form-check-input" id="egg_yolk_area" name="area_type" value="egg_yolk" required>
                                            <label class="form-check-label" for="egg_yolk_area">蛋黃區</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="radio" class="form-check-input" id="egg_white_area" name="area_type" value="egg_white" required>
                                            <label class="form-check-label" for="egg_white_area">蛋白區</label>
                                        </div>
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
